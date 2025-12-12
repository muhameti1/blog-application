<?php

namespace App\Traits\Model;

trait UserRolesTrait
{
    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is an author.
     */
    public function isAuthor(): bool
    {
        return $this->role === 'author';
    }

    /**
     * Check if user is a reader.
     */
    public function isReader(): bool
    {
        return $this->role === 'reader';
    }

    /**
     * Check if user can approve posts.
     */
    public function canApprovePosts(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user can create posts.
     */
    public function canCreatePosts(): bool
    {
        return $this->isAdmin() || $this->isAuthor();
    }
}
