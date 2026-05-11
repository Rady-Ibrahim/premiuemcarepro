<?php

namespace App\Listeners;

use App\Events\MessageSent;
use App\Jobs\SendPushNotificationJob;

class SendPushNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        $message = $event->message;
        $conversation = $message->conversation;

        // Get the recipient (the user who didn't send the message)
        $recipientId = $conversation->user1_id === $message->sender_id 
            ? $conversation->user2_id 
            : $conversation->user1_id;

        // Dispatch job to send push notification
        SendPushNotificationJob::dispatch($message, $recipientId);
    }
}
