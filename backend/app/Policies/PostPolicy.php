<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine if the user can view any posts.
     */
    public function viewAny(?User $user): bool
    {
        // Anyone can view approved posts
        return true;
    }

    /**
     * Determine if the user can view the post.
     */
    public function view(?User $user, Post $post): bool
    {
        // Anyone can view approved posts
        if ($post->isApproved()) {
            return true;
        }

        // Unauthenticated users can only see approved posts
        if (!$user) {
            return false;
        }

        // Admin can view any post
        if ($user->isAdmin()) {
            return true;
        }

        // Author can view their own posts
        return $post->user_id === $user->id;
    }

    /**
     * Determine if the user can create posts.
     */
    public function create(User $user): bool
    {
        // Only admins and authors can create posts
        return $user->isAdmin() || $user->isAuthor();
    }

    /**
     * Determine if the user can update the post.
     */
    public function update(User $user, Post $post): bool
    {
        // Admin can update any post
        if ($user->isAdmin()) {
            return true;
        }

        // Author can update their own posts
        return $post->user_id === $user->id;
    }

    /**
     * Determine if the user can delete the post.
     */
    public function delete(User $user, Post $post): bool
    {
        // Admin can delete any post
        if ($user->isAdmin()) {
            return true;
        }

        // Author can delete their own posts
        return $post->user_id === $user->id;
    }

    /**
     * Determine if the user can approve posts.
     */
    public function approve(User $user, Post $post): bool
    {
        // Only admin can approve posts
        return $user->isAdmin();
    }

    /**
     * Determine if the user can reject posts.
     */
    public function reject(User $user, Post $post): bool
    {
        // Only admin can reject posts
        return $user->isAdmin();
    }
}
