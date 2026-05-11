<?php

namespace App\Http\Middleware;

use App\Models\Conversation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsPartOfConversation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $conversationId = $request->route('id') ?? $request->route('conversation');

        if (!$conversationId) {
            return response()->json([
                'success' => false,
                'message' => 'Conversation ID is required',
            ], 400);
        }

        $conversation = Conversation::find($conversationId);

        if (!$conversation) {
            return response()->json([
                'success' => false,
                'message' => 'Conversation not found',
            ], 404);
        }

        $userId = auth()->id();

        if ($conversation->user1_id !== $userId && $conversation->user2_id !== $userId) {
            return response()->json([
                'success' => false,
                'message' => 'You are not part of this conversation',
            ], 403);
        }

        return $next($request);
    }
}
