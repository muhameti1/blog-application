<?php

namespace App\Services;

use App\Models\NewsletterSubscriber;
use Illuminate\Database\Eloquent\Collection;

class NewsletterService
{
    /**
     * Subscribe an email to the newsletter.
     */
    public function subscribe(string $email): NewsletterSubscriber
    {
        // Check if already subscribed
        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if ($subscriber) {
            // If previously unsubscribed, resubscribe
            if ($subscriber->unsubscribed_at !== null) {
                $subscriber->unsubscribed_at = null;
                $subscriber->subscribed_at = now();
                $subscriber->save();
            }
            return $subscriber;
        }

        // Create new subscriber
        return NewsletterSubscriber::create([
            'email' => $email,
        ]);
    }

    /**
     * Unsubscribe using token.
     */
    public function unsubscribe(string $token): ?NewsletterSubscriber
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->first();

        if (!$subscriber) {
            return null;
        }

        if ($subscriber->unsubscribed_at === null) {
            $subscriber->unsubscribed_at = now();
            $subscriber->save();
        }

        return $subscriber;
    }

    /**
     * Get all active subscribers.
     */
    public function getActiveSubscribers(): Collection
    {
        return NewsletterSubscriber::active()->get();
    }

    /**
     * Check if email is subscribed.
     */
    public function isSubscribed(string $email): bool
    {
        return NewsletterSubscriber::where('email', $email)
            ->active()
            ->exists();
    }

    /**
     * Get subscriber count.
     */
    public function getSubscriberCount(): int
    {
        return NewsletterSubscriber::active()->count();
    }
}
