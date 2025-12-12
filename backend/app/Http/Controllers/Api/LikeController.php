<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use App\Services\LikeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct(
        private readonly LikeService $likeService
    ) {}

    /**
     * Toggle like on a post.
     */
    public function toggle(Post $post, Request $request): JsonResponse
    {
        $this->authorize('create', Like::class);

        $result = $this->likeService->toggleLike($post, $request->user());

        return response()->json([
            'message' => $result['liked'] ? 'Post liked' : 'Post unliked',
            'liked' => $result['liked'],
            'likes_count' => $result['likes_count'],
        ]);
    }

    /**
     * Check if user has liked a post.
     */
    public function checkLike(Post $post, Request $request): JsonResponse
    {
        $liked = $this->likeService->hasLiked($post, $request->user());
        $likesCount = $this->likeService->getLikeCount($post);

        return response()->json([
            'liked' => $liked,
            'likes_count' => $likesCount,
        ]);
    }
}
