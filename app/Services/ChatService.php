<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ChatService
{
    public function createOrGetConversation($user1Id, $user2Id)
    {
        // Ensure user1_id is always smaller to maintain uniqueness
        $userId1 = min($user1Id, $user2Id);
        $userId2 = max($user1Id, $user2Id);

        $conversation = Conversation::where('user1_id', $userId1)
            ->where('user2_id', $userId2)
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user1_id' => $userId1,
                'user2_id' => $userId2,
            ]);
        }

        return $conversation->load(['user1', 'user2', 'lastMessage']);
    }

    public function sendMessage($conversationId, $senderId, $content = null, $file = null)
    {
        DB::beginTransaction();
        try {
            $message = Message::create([
                'conversation_id' => $conversationId,
                'sender_id' => $senderId,
                'content' => $content,
                'type' => $file ? 'file' : 'text',
                'file_path' => $file ? $this->uploadFile($file, $conversationId)['path'] : null,
                'file_name' => $file ? $file->getClientOriginalName() : null,
                'file_size' => $file ? $file->getSize() : null,
                'is_read' => false,
            ]);

            // Update conversation's last message and timestamp
            $conversation = Conversation::find($conversationId);
            $conversation->update([
                'last_message_id' => $message->id,
                'updated_at' => now(),
            ]);

            // Broadcast the message
            event(new MessageSent($message));

            // Check if we should send email notification
            $this->checkAndSendUnreadNotifications();

            DB::commit();
            return $message;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function markMessageAsRead($messageId, $userId)
    {
        $message = Message::findOrFail($messageId);

        // Only the recipient can mark as read
        $conversation = $message->conversation;
        if ($conversation->user1_id === $userId && $message->sender_id === $userId) {
            throw new \Exception('Cannot mark your own message as read');
        }

        if ($conversation->user2_id === $userId && $message->sender_id === $userId) {
            throw new \Exception('Cannot mark your own message as read');
        }

        $message->markAsRead();

        return $message;
    }

    public function markConversationAsRead($conversationId, $userId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        if (!$conversation->isPartOf($userId)) {
            throw new \Exception('User is not part of this conversation');
        }

        $messages = Message::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->get();

        foreach ($messages as $message) {
            $message->markAsRead();
        }

        return $messages;
    }

    public function deleteMessage($messageId, $userId)
    {
        $message = Message::findOrFail($messageId);

        // Only sender can delete their own message
        if ($message->sender_id !== $userId) {
            throw new \Exception('You can only delete your own messages');
        }

        $message->delete();

        return $message;
    }

    public function getConversation($conversationId, $userId, $limit = 50, $beforeId = null)
    {
        $conversation = Conversation::findOrFail($conversationId);

        // Check if user is part of conversation
        if ($conversation->user1_id !== $userId && $conversation->user2_id !== $userId) {
            throw new \Exception('User is not part of this conversation');
        }

        $query = Message::where('conversation_id', $conversationId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->limit($limit);

        if ($beforeId) {
            $query->where('id', '<', $beforeId);
        }

        $messages = $query->get();

        return [
            'conversation' => $conversation->load(['user1', 'user2']),
            'messages' => $messages,
        ];
    }

    public function getConversations($userId, $limit = 20)
    {
        $conversations = Conversation::forUser($userId)
            ->with(['user1', 'user2', 'lastMessage.sender'])
            ->orderBy('updated_at', 'desc')
            ->paginate($limit);

        // Add unread count for each conversation
        foreach ($conversations as $conversation) {
            $unreadCount = Message::where('conversation_id', $conversation->id)
                ->where('sender_id', '!=', $userId)
                ->where('is_read', false)
                ->count();
            $conversation->unread_count = $unreadCount;
        }

        return $conversations;
    }

    public function getUnreadCount($userId)
    {
        $conversationIds = Conversation::forUser($userId)->pluck('id');

        return Message::whereIn('conversation_id', $conversationIds)
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }

    private function uploadFile($file, $conversationId)
    {
        $validator = Validator::make(['file' => $file], [
            'file' => 'required|max:51200|mimes:pdf,docx,xlsx,jpg,png,jpeg,gif,webp',
        ]);

        if ($validator->fails()) {
            throw new \Exception('Invalid file. Max size: 50MB. Allowed types: pdf, docx, xlsx, jpg, png, jpeg, gif, webp');
        }

        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '_' . uniqid() . '.' . $extension;
        $filePath = "chat/{$conversationId}/{$fileName}";

        Storage::disk('chat_uploads')->put($filePath, file_get_contents($file));

        $type = 'file';
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $type = 'image';
        }

        return [
            'path' => $filePath,
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'type' => $type,
        ];
    }

    private function updateConversationLastMessage($conversationId, $messageId)
    {
        Conversation::where('id', $conversationId)->update([
            'last_message_id' => $messageId,
            'updated_at' => now(),
        ]);
    }
}
