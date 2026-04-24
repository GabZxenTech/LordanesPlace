<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * User sends a message
     */
    public function send(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string',
            ]);

            $userId = Auth::id();
            $sessionId = session()->getId();
            
            // Log for debug
            \Log::info("Chat send attempt", ['user_id' => $userId, 'session_id' => $sessionId, 'message' => $request->message]);

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
            \Log::error("Chat send error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
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

        Conversation::find($request->conversation_id)->update(['last_message_at' => now()]);

        return response()->json(['success' => true, 'message' => $message]);
    }

    /**
     * Get messages for a conversation (polling)
     */
    public function getMessages(Request $request)
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        if ($userId) {
            $conversation = Conversation::where('user_id', $userId)->first();
        } else {
            $conversation = Conversation::where('session_id', $sessionId)->first();
        }

        if (!$conversation) {
            return response()->json(['messages' => []]);
        }

        $lastId = $request->query('last_id', 0);
        $messages = Message::where('conversation_id', $conversation->id)
            ->where('id', '>', $lastId)
            ->get();

        return response()->json(['messages' => $messages]);
    }

    /**
     * Admin: Get all conversations
     */
    public function adminConversations()
    {
        $conversations = Conversation::with(['user', 'messages' => function($q) {
            $q->latest()->limit(1);
        }])->orderBy('last_message_at', 'desc')->get();

        return view('admin.chat', compact('conversations'));
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
        $messages = Message::where('conversation_id', $id)->get();
        return response()->json(['messages' => $messages]);
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
