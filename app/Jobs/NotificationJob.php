<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class NotificationJob implements ShouldQueue
{
    use Queueable;

    public $title;
    public $body;
    public $userId;
    public $type;


    public function __construct($title, $body, $userId, $type)
    {
        $this->title = $title;
        $this->body = $body;
        $this->userId = $userId;
        $this->type = $type;
    }


    public function handle(): void
    {
        Log::info('NotificationJob started', [
            'title' => $this->title,
            'body' => $this->body,
            'userId' => $this->userId,
            'type' => $this->type,
        ]);

        Notification::create([
            'title' => $this->title,
            'body' => $this->body,
            'user_id' => $this->userId,
            'type' => $this->type,
            'is_read' => false,
        ]);
    }
}
