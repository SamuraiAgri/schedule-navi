<?php

namespace App\Jobs;

use App\Mail\NewResponseNotification;
use App\Models\Event;
use App\Models\Response;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

/**
 * 新規回答通知メール送信ジョブ
 */
class SendResponseNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Event $event,
        public Response $response,
    ) {}

    public function handle(): void
    {
        if (!$this->event->notify_email) {
            return;
        }

        Mail::to($this->event->notify_email)
            ->send(new NewResponseNotification($this->event, $this->response));
    }
}
