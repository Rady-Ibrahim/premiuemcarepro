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
     * Get messages for the authenticated user's admin conversation
     */
    public function messages(Request $request)
    {
        $userId = Auth::id();
        $limit = $request->get('limit', 50);
        $beforeId = $request->get('before_id');
        $afterId = $request->get('after');

        try {
            $result = $this->chatService->getAdminConversationMessages($userId, $limit, $beforeId, $afterId);

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Send a message to admin (auto-creates conversation)
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'content' => 'required_without:file|string',
            'file' => 'nullable|file|max:51200|mimes:pdf,docx,xlsx,jpg,png,jpeg,gif,webp',
        ]);

        $userId = Auth::id();

        try {
            $message = $this->chatService->sendMessageToAdmin(
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
     * Mark all messages in the admin conversation as read
     */
    public function markAllAsRead()
    {
        $userId = Auth::id();

        try {
            $messages = $this->chatService->markAllAsReadForUser($userId);

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
