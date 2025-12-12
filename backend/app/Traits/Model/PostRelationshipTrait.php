<?php

namespace App\Traits\Model;

use App\Models\Comment;
use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait PostRelationshipTrait
{
    /**
     * Get the user who created this post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who approved this post.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all comments for this post.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all likes for this post.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
}
