<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Comment $comment): bool
    {
        // All comments are public
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can comment
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        // Users can edit their own comments, admins can edit any
        return $comment->user_id === $user->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // Users can delete their own comments, admins can delete any
        return $comment->user_id === $user->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can approve comments.
     */
    public function approve(User $user, Comment $comment): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can reject comments.
     */
    public function reject(User $user, Comment $comment): bool
    {
        return $user->isAdmin();
    }
}
