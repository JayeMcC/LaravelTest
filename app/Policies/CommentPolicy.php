<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine whether the user can view any comments.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view a specific comment.
     *
     * @param  User  $user
     * @param  Comment  $comment
     * @return bool
     */
    public function view(User $user, Comment $comment): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create a comment.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can update a specific comment.
     *
     * @param  User  $user
     * @param  Comment  $comment
     * @return bool
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can delete a specific comment.
     *
     * @param  User  $user
     * @param  Comment  $comment
     * @return bool
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can restore a specific comment.
     *
     * @param  User  $user
     * @param  Comment  $comment
     * @return bool
     */
    public function restore(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete a specific comment.
     *
     * @param  User  $user
     * @param  Comment  $comment
     * @return bool
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->isAdmin();
    }
}
