<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class PostService
{
    /**
     * Get all approved and published posts.
     */
    public function getAllPublishedPosts(): Collection
    {
        return Post::published()
            ->with(['user'])
            ->withCount(['comments', 'likes'])
            ->orderBy('published_at', 'desc')
            ->get();
    }

    /**
     * Get a single post by slug.
     */
    public function getPostBySlug(string $slug): ?Post
    {
        return Post::where('slug', $slug)
            ->with(['user'])
            ->withCount(['comments', 'likes'])
            ->first();
    }

    /**
     * Get all posts for a specific user.
     */
    public function getUserPosts(User $user): Collection
    {
        return Post::where('user_id', $user->id)
            ->with(['approver'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all posts (for admin).
     */
    public function getAllPosts(): Collection
    {
        return Post::with(['user', 'approver'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all pending posts (for admin).
     */
    public function getPendingPosts(): Collection
    {
        return Post::pending()
            ->with(['user'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Create a new post.
     */
    public function createPost(array $data, User $user): Post
    {
        $slug = $this->generateUniqueSlug($data['title']);

        $post = new Post();
        $post->user_id = $user->id;
        $post->title = $data['title'];
        $post->slug = $slug;
        $post->content = $data['content'];
        $post->excerpt = $data['excerpt'] ?? Str::limit(strip_tags($data['content']), 150);

        // Auto-approve for admins, pending for authors
        if ($user->isAdmin()) {
            $post->status = 'approved';
            $post->approved_by = $user->id;
            $post->approved_at = now();
            $post->published_at = now();
        } else {
            $post->status = $data['status'] ?? 'pending';
        }

        $post->save();

        // TODO: Dispatch newsletter job if approved
        // if ($post->isApproved()) {
        //     SendPostNotificationJob::dispatch($post);
        // }

        return $post->load(['user', 'approver']);
    }

    /**
     * Update an existing post.
     */
    public function updatePost(Post $post, array $data, User $user): Post
    {
        // If title changed, regenerate slug
        if (isset($data['title']) && $data['title'] !== $post->title) {
            $post->slug = $this->generateUniqueSlug($data['title'], $post->id);
        }

        $post->title = $data['title'] ?? $post->title;
        $post->content = $data['content'] ?? $post->content;
        $post->excerpt = $data['excerpt'] ?? Str::limit(strip_tags($data['content'] ?? $post->content), 150);

        // Only allow status change for drafts
        if (isset($data['status']) && $post->isDraft()) {
            $post->status = $data['status'];
        }

        $post->save();

        return $post->load(['user', 'approver']);
    }

    /**
     * Delete a post.
     */
    public function deletePost(Post $post): bool
    {
        return $post->delete();
    }

    /**
     * Approve a post.
     */
    public function approvePost(Post $post, User $admin): Post
    {
        $post->status = 'approved';
        $post->approved_by = $admin->id;
        $post->approved_at = now();
        $post->published_at = $post->published_at ?? now();
        $post->save();

        // TODO: Dispatch newsletter job
        // SendPostNotificationJob::dispatch($post);

        return $post->load(['user', 'approver']);
    }

    /**
     * Reject a post.
     */
    public function rejectPost(Post $post, User $admin): Post
    {
        $post->status = 'rejected';
        $post->approved_by = $admin->id;
        $post->approved_at = now();
        $post->save();

        return $post->load(['user', 'approver']);
    }

    /**
     * Generate a unique slug from title.
     */
    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if slug exists.
     */
    private function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = Post::where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
