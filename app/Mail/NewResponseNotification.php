<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\Response;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * 新規回答通知メール
 */
class NewResponseNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Event $event,
        public Response $response,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "【Schedule-Navi】{$this->event->title}に新しい回答がありました",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-response',
            with: [
                'event' => $this->event,
                'response' => $this->response,
                'eventUrl' => $this->event->share_url,
                'editUrl' => $this->event->edit_url,
            ],
        );
    }
}
