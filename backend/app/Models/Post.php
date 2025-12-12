<?php

namespace App\Models;

use App\Traits\Model\PostRelationshipTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, PostRelationshipTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'status',
        'approved_by',
        'approved_at',
        'published_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Scope to get only approved posts.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get only pending posts.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get published posts (approved and published_at is in the past).
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'approved')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Check if post is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if post is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if post is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if post is draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }
}
