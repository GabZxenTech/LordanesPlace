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
    /**
     * User sends a message
     */
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
            // Details stay in the server log; the client only gets a generic
            // message so internal errors (SQL, paths) are never exposed.
            \Log::error("Chat send error: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Message could not be sent. Please try again.'], 500);
        }
    }

    /**
     * Admin sends a reply
     */
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

        // Replying means the admin has read the conversation.
        Conversation::find($request->conversation_id)->forceFill(['last_message_at' => now(), 'admin_read_at' => now()])->save();

        return response()->json(['success' => true, 'message' => $message]);
    }

    /**
     * Get messages for a conversation (polling)
     */
    public function getMessages(Request $request)
    {
        $conversation = $this->currentConversation();

        if (!$conversation) {
            return response()->json(['messages' => []]);
        }

        // Only the open chat window asks for messages, so the customer has
        // now seen the admin's replies (clears the red dot on the bubble).
        $conversation->markReadByCustomer();

        $lastId = $request->query('last_id', 0);
        $messages = Message::where('conversation_id', $conversation->id)
            ->where('id', '>', $lastId)
            ->get();

        return response()->json(['messages' => $messages]);
    }

    /**
     * Whether the admin replied since the customer/guest last opened the
     * chat; the closed chat bubble polls this to show its red dot.
     */
    public function unread()
    {
        $conversation = $this->currentConversation();

        return response()->json(['unread' => $conversation ? $conversation->hasUnreadForCustomer() : false]);
    }

    /** The conversation of the logged-in user, or of this guest's session. */
    private function currentConversation(): ?Conversation
    {
        return Auth::id()
            ? Conversation::where('user_id', Auth::id())->first()
            : Conversation::where('session_id', session()->getId())->first();
    }

    /**
     * Admin: Get all conversations
     */
    public function adminConversations()
    {
        $conversations = Conversation::with(['user', 'messages' => function($q) {
            $q->latest()->limit(1);
        }])->orderBy('last_message_at', 'desc')->get();

        // Which conversations have messages the admin hasn't read yet.
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

    /**
     * Admin: Open a specific conversation
     */
    public function adminOpenChat($id)
    {
        $conversation = Conversation::findOrFail($id);

        // Opening (and the 5-second refresh while it stays open) counts as
        // read, which clears it from the unread badge.
        $conversation->markReadByAdmin();

        $messages = Message::where('conversation_id', $conversation->id)->get();
        return response()->json(['messages' => $messages, 'unread_chats' => Conversation::unreadCountForAdmin()]);
    }

    /**
     * Admin: permanently delete the selected conversations and all their
     * messages. The customer's chat history goes with it; if they message
     * again, a new conversation is started.
     */
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

    /**
     * Admin: Toggle online status
     */
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

    /**
     * Client: Check admin status
     */
    public function getAdminStatus()
    {
        // Assuming the first admin is the one we track, or any admin online
        $isOnline = User::where('role', 'admin')->where('is_online', true)->exists();
        return response()->json(['is_online' => $isOnline]);
    }
}
