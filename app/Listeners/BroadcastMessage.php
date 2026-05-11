<?php

namespace App\Listeners;

use App\Events\MessageSent;

class BroadcastMessage
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
        // The event is automatically broadcasted via Redis/WebSocket
        // This listener can be used for additional logging or analytics
    }
}
