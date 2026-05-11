<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ChatService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function index()
    {
        $userId = Auth::id();
        $conversations = $this->chatService->getConversations($userId, 50);
        $unreadCount = $this->chatService->getUnreadCount($userId);

        return view('admin.chat.index', compact('conversations', 'unreadCount'));
    }

    public function getUsers()
    {
        $users = User::where('id', '!=', Auth::id())
            ->where('type', 'customer')
            ->get(['id', 'name', 'email']);

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    public function getConversations()
    {
        $userId = Auth::id();
        $conversations = $this->chatService->getConversations($userId, 50);

        return response()->json([
            'success' => true,
            'data' => $conversations->items(),
        ]);
    }

    public function createConversation(Request $request)
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

    public function getMessages($id)
    {
        $userId = Auth::id();
        $limit = request()->get('limit', 50);
        $afterId = request()->get('after');

        \Log::info("getMessages called", ['conversation_id' => $id, 'user_id' => $userId, 'limit' => $limit, 'after' => $afterId]);

        try {
            $result = $this->chatService->getConversation($id, $userId, $limit, null, $afterId);

            \Log::info("getMessages result", ['result' => $result]);

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            \Log::error("getMessages error", ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }

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

    public function uploadFile(Request $request)
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

    public function markAsRead($id)
    {
        $userId = Auth::id();

        \Log::info("markAsRead called", ['conversation_id' => $id, 'user_id' => $userId]);

        try {
            $messages = $this->chatService->markConversationAsRead($id, $userId);

            return response()->json([
                'success' => true,
                'data' => $messages,
            ]);
        } catch (\Exception $e) {
            \Log::error("markAsRead error", ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getFile($path)
    {
        $filePath = storage_path('app/uploads/chat/' . $path);
        
        // Debug logging
        \Log::info("getFile called with path: " . $path);
        \Log::info("Constructed filePath: " . $filePath);
        \Log::info("File exists: " . (file_exists($filePath) ? 'YES' : 'NO'));

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found', 'path' => $path, 'file_path' => $filePath], 404);
        }

        return response()->file($filePath);
    }
}
