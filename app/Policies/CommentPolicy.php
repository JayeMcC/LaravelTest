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
   * @return bool
   */
  public function viewAny(): bool
  {
    return true;
  }

  /**
   * Determine whether the user can view a specific comment.
   *
   * @return bool
   */
  public function view(): bool
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
    return $user->id === $comment->user_id || $user->isAdmin();;
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
    return $user->id === $comment->user_id || $user->isAdmin();;
  }
}
