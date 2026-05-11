<?php

/**
 * Update Conversations with Last Message
 * Run with: php update_conversations.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Conversation;
use App\Models\Message;

echo "=== Updating Conversations with Last Message ===\n\n";

$conversations = Conversation::all();

foreach ($conversations as $conversation) {
    // Get the latest message for this conversation
    $lastMessage = Message::where('conversation_id', $conversation->id)
        ->orderBy('created_at', 'desc')
        ->first();

    if ($lastMessage) {
        $conversation->last_message_id = $lastMessage->id;
        $conversation->save();

        echo "Conversation ID: {$conversation->id} - Last Message ID: {$lastMessage->id}\n";
    } else {
        echo "Conversation ID: {$conversation->id} - No messages found\n";
    }
}

echo "\n=== Update Complete ===\n";
