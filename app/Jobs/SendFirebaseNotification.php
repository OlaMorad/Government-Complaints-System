<?php

namespace App\Jobs;

use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\http\Services\FirebaseNotificationService;

class SendFirebaseNotification implements ShouldQueue
{
    use Queueable;

    public $userId;
    public $title;
    public $body;
    public $data;

    public function __construct(int $userId, string $title, string $body, array $data = [])
    {
        $this->userId = $userId;
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
    }

    public function handle(FirebaseNotificationService $firebase)
    {
        $firebase->sendToUser($this->userId, $this->title, $this->body, $this->data);
    }
}
