<?php

namespace App\Traits\Model;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserRelationshipTrait
{
    /**
     * Get all posts created by this user.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get all comments created by this user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all posts approved by this admin.
     */
    public function approvedPosts(): HasMany
    {
        return $this->hasMany(Post::class, 'approved_by');
    }
}
