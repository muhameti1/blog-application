<?php

namespace App\Services;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;

class LikeService
{
    /**
     * Toggle like on a post.
     */
    public function toggleLike(Post $post, User $user): array
    {
        $like = Like::where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->first();

        if ($like) {
            // Unlike
            $like->delete();
            $liked = false;
        } else {
            // Like
            Like::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);
            $liked = true;
        }

        // Get updated like count
        $likesCount = $post->likes()->count();

        return [
            'liked' => $liked,
            'likes_count' => $likesCount,
        ];
    }

    /**
     * Check if user has liked a post.
     */
    public function hasLiked(Post $post, ?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return Like::where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Get like count for a post.
     */
    public function getLikeCount(Post $post): int
    {
        return $post->likes()->count();
    }
}
