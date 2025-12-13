<?php

namespace App\Mail;

use App\Models\Post;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PostPublishedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Post $post,
        public NewsletterSubscriber $subscriber
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Post: ' . $this->post->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $frontendUrl = config('app.frontend_url', 'http://localhost:3000');

        return new Content(
            view: 'emails.post-published',
            with: [
                'post' => $this->post,
                'subscriber' => $this->subscriber,
                'postUrl' => $frontendUrl . '/posts/' . $this->post->slug,
                'unsubscribeUrl' => $frontendUrl . '/newsletter/unsubscribe/' . $this->subscriber->token,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
