<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\UnreadChatNotification;

class SendUnreadChatNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $unreadCount;
    protected $adminEmail;

    /**
     * Create a new job instance.
     */
    public function __construct($unreadCount, $adminEmail)
    {
        $this->unreadCount = $unreadCount;
        $this->adminEmail = $adminEmail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->unreadCount > 0) {
            Mail::to($this->adminEmail)->send(new UnreadChatNotification($this->unreadCount));
        }
    }
}
