<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Get all conversations for the authenticated user
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $limit = $request->get('limit', 20);

        $conversations = $this->chatService->getConversations($userId, $limit);

        return response()->json([
            'success' => true,
            'data' => $conversations,
        ]);
    }

    /**
     * Create or get a conversation
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|different:' . Auth::id(),
        ]);

        $userId = Auth::id();
        $otherUserId = $request->user_id;

        $conversation = $this->chatService->createOrGetConversation($userId, $otherUserId);

        return response()->json([
            'success' => true,
            'data' => $conversation,
        ]);
    }

    /**
     * Get a specific conversation with messages
     */
    public function show(Request $request, $id)
    {
        $userId = Auth::id();
        $limit = $request->get('limit', 50);
        $beforeId = $request->get('before_id');

        try {
            $result = $this->chatService->getConversation($id, $userId, $limit, $beforeId);

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    /**
     * Delete a conversation
     */
    public function destroy($id)
    {
        $userId = Auth::id();
        $conversation = \App\Models\Conversation::findOrFail($id);

        if ($conversation->user1_id !== $userId && $conversation->user2_id !== $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $conversation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Conversation deleted successfully',
        ]);
    }

    /**
     * Get messages for a conversation
     */
    public function messages(Request $request, $id)
    {
        $userId = Auth::id();
        $limit = $request->get('limit', 50);
        $beforeId = $request->get('before_id');

        try {
            $result = $this->chatService->getConversation($id, $userId, $limit, $beforeId);

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'required_without:file|string',
            'file' => 'nullable|file|max:51200|mimes:pdf,docx,xlsx,jpg,png,jpeg,gif,webp',
        ]);

        $userId = Auth::id();

        try {
            $message = $this->chatService->sendMessage(
                $request->conversation_id,
                $userId,
                $request->content,
                $request->file('file')
            );

            return response()->json([
                'success' => true,
                'data' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Mark a message as read
     */
    public function markAsRead(Request $request, $id)
    {
        $userId = Auth::id();

        try {
            $message = $this->chatService->markMessageAsRead($id, $userId);

            return response()->json([
                'success' => true,
                'data' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Mark all messages in a conversation as read
     */
    public function markConversationAsRead(Request $request, $id)
    {
        $userId = Auth::id();

        try {
            $messages = $this->chatService->markConversationAsRead($id, $userId);

            return response()->json([
                'success' => true,
                'data' => $messages,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete a message
     */
    public function deleteMessage($id)
    {
        $userId = Auth::id();

        try {
            $message = $this->chatService->deleteMessage($id, $userId);

            return response()->json([
                'success' => true,
                'data' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Upload a file
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:51200|mimes:pdf,docx,xlsx,jpg,png,jpeg,gif,webp',
            'conversation_id' => 'required|exists:conversations,id',
        ]);

        $userId = Auth::id();
        $conversationId = $request->conversation_id;

        try {
            $message = $this->chatService->sendMessage(
                $conversationId,
                $userId,
                null,
                $request->file('file')
            );

            return response()->json([
                'success' => true,
                'data' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get unread messages count
     */
    public function unreadCount()
    {
        $userId = Auth::id();
        $count = $this->chatService->getUnreadCount($userId);

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $count,
            ],
        ]);
    }
}
