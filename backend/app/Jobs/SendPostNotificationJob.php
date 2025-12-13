<?php

namespace App\Jobs;

use App\Mail\PostPublishedMail;
use App\Models\Post;
use App\Services\NewsletterService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendPostNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Post $post
    ) {}

    /**
     * Execute the job.
     */
    public function handle(NewsletterService $newsletterService): void
    {
        $subscribers = $newsletterService->getActiveSubscribers();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)
                ->send(new PostPublishedMail($this->post, $subscriber));
        }
    }
}
