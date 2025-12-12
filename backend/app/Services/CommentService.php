<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CommentService
{
    /**
     * Get all comments for a post.
     */
    public function getPostComments(Post $post): Collection
    {
        return Comment::where('post_id', $post->id)
            ->topLevel()
            ->with(['user', 'replies' => function ($query) {
                $query->with('user')->orderBy('created_at', 'asc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all comments (for admin).
     */
    public function getAllComments(): Collection
    {
        return Comment::with(['user', 'post', 'approver'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get pending comments (for admin).
     */
    public function getPendingComments(): Collection
    {
        return Comment::pending()
            ->with(['user', 'post'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get user's comments.
     */
    public function getUserComments(User $user): Collection
    {
        return Comment::where('user_id', $user->id)
            ->with(['post', 'approver'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create a new comment.
     */
    public function createComment(array $data, User $user): Comment
    {
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->post_id = $data['post_id'];
        $comment->parent_id = $data['parent_id'] ?? null;
        $comment->content = $data['content'];
        $comment->save();

        return $comment->load(['user', 'post']);
    }

    /**
     * Update a comment.
     */
    public function updateComment(Comment $comment, array $data, User $user): Comment
    {
        $comment->content = $data['content'];
        $comment->save();

        return $comment->load(['user', 'post']);
    }

    /**
     * Delete a comment.
     */
    public function deleteComment(Comment $comment): void
    {
        $comment->delete();
    }

    /**
     * Approve a comment.
     */
    public function approveComment(Comment $comment, User $approver): Comment
    {
        $comment->status = 'approved';
        $comment->approved_by = $approver->id;
        $comment->approved_at = now();
        $comment->save();

        return $comment->load(['user', 'post', 'approver']);
    }

    /**
     * Reject a comment.
     */
    public function rejectComment(Comment $comment, User $approver): Comment
    {
        $comment->status = 'rejected';
        $comment->approved_by = $approver->id;
        $comment->approved_at = now();
        $comment->save();

        return $comment->load(['user', 'post', 'approver']);
    }
}
