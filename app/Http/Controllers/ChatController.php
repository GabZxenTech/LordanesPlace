<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    // Customer or guest sends a message.
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        try {
            $userId = Auth::id();
            $sessionId = session()->getId();

            if ($userId) {
                $conversation = Conversation::firstOrCreate(
                    ['user_id' => $userId],
                    ['last_message_at' => now()]
                );
            } else {
                $conversation = Conversation::firstOrCreate(
                    ['session_id' => $sessionId],
                    ['last_message_at' => now()]
                );
            }

            $conversation->update(['last_message_at' => now()]);

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $userId,
                'role' => 'user',
                'body' => $request->message,
            ]);

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            // Log the real error; show a generic message to the user.
            \Log::error("Chat send error: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Message could not be sent. Please try again.'], 500);
        }
    }

    // Admin sends a reply.
    public function adminReply(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string',
        ]);

        $admin = Auth::user();

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => $admin->id,
            'role' => 'admin',
            'body' => $request->message,
        ]);

        // Replying marks the conversation as read.
        Conversation::find($request->conversation_id)->forceFill(['last_message_at' => now(), 'admin_read_at' => now()])->save();

        return response()->json(['success' => true, 'message' => $message]);
    }

    // Messages for the customer's chat window (polling).
    public function getMessages(Request $request)
    {
        $conversation = $this->currentConversation();

        if (!$conversation) {
            return response()->json(['messages' => []]);
        }

        // The chat is open, so the admin's replies are now read.
        $conversation->markReadByCustomer();

        $lastId = $request->query('last_id', 0);
        $messages = Message::where('conversation_id', $conversation->id)
            ->where('id', '>', $lastId)
            ->get();

        return response()->json(['messages' => $messages]);
    }

    // Whether the admin has a reply the customer hasn't seen (red dot on the chat bubble).
    public function unread()
    {
        $conversation = $this->currentConversation();

        return response()->json(['unread' => $conversation ? $conversation->hasUnreadForCustomer() : false]);
    }

    // The logged-in user's conversation, or the guest's (by session).
    private function currentConversation(): ?Conversation
    {
        return Auth::id()
            ? Conversation::where('user_id', Auth::id())->first()
            : Conversation::where('session_id', session()->getId())->first();
    }

    // Admin: list of conversations.
    public function adminConversations()
    {
        $conversations = Conversation::with(['user', 'messages' => function($q) {
            $q->latest()->limit(1);
        }])->orderBy('last_message_at', 'desc')->get();

        // Conversations with unread messages.
        $unreadIds = Conversation::unreadForAdmin()->pluck('id')->all();

        return view('admin.chat', compact('conversations', 'unreadIds'));
    }

    public function adminConversationsJson()
    {
        $conversations = Conversation::with(['user', 'messages' => function($q) {
            $q->latest()->limit(1);
        }])->orderBy('last_message_at', 'desc')->get();

        return response()->json(['conversations' => $conversations]);
    }

    // Admin: open a conversation (marks it as read).
    public function adminOpenChat($id)
    {
        $conversation = Conversation::findOrFail($id);

        $conversation->markReadByAdmin();

        $messages = Message::where('conversation_id', $conversation->id)->get();
        return response()->json(['messages' => $messages, 'unread_chats' => Conversation::unreadCountForAdmin()]);
    }

    // Admin: permanently delete the selected conversations and their messages.
    public function adminDeleteConversations(Request $request)
    {
        $data = $request->validate([
            'ids'   => 'required|array|min:1|max:500',
            'ids.*' => 'integer|distinct',
        ]);

        $deleted = DB::transaction(function () use ($data) {
            Message::whereIn('conversation_id', $data['ids'])->delete();
            return Conversation::whereIn('id', $data['ids'])->delete();
        });

        return response()->json([
            'success' => true,
            'deleted' => $deleted,
            'unread_chats' => Conversation::unreadCountForAdmin(),
        ]);
    }

    // Admin: toggle online status.
    public function toggleStatus()
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user->is_online = !$user->is_online;
        $user->save();

        return response()->json(['success' => true, 'is_online' => $user->is_online]);
    }

    // Whether an admin is online.
    public function getAdminStatus()
    {
        $isOnline = User::where('role', 'admin')->where('is_online', true)->exists();
        return response()->json(['is_online' => $isOnline]);
    }
}
