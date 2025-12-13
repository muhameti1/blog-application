<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsletterStatusRequest;
use App\Http\Requests\NewsletterSubscribeRequest;
use App\Http\Resources\NewsletterSubscriberResource;
use App\Services\NewsletterService;

class NewsletterController extends Controller
{
    public function __construct(
        private NewsletterService $newsletterService
    ) {}

    public function subscribe(NewsletterSubscribeRequest $request)
    {
        try {
            $subscriber = $this->newsletterService->subscribe($request->validated()['email']);

            return (new NewsletterSubscriberResource($subscriber))
                ->additional([
                    'message' => 'Successfully subscribed to newsletter!'
                ])
                ->response()
                ->setStatusCode(201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to subscribe. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function unsubscribe(string $token)
    {
        try {
            $subscriber = $this->newsletterService->unsubscribe($token);

            if (!$subscriber) {
                return response()->json([
                    'message' => 'Invalid or expired unsubscribe link.'
                ], 404);
            }

            return response()->json([
                'message' => 'Successfully unsubscribed from newsletter.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to unsubscribe. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function status(NewsletterStatusRequest $request)
    {
        $isSubscribed = $this->newsletterService->isSubscribed($request->validated()['email']);

        return response()->json([
            'subscribed' => $isSubscribed
        ]);
    }
}
