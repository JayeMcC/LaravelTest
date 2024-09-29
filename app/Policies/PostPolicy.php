<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
  /**
   * Determine whether the user can view any posts.
   *
   * @param  User  $user
   * @return bool
   */
  public function viewAny(User $user): bool
  {
    return true;
  }

  /**
   * Determine whether the user can view a specific post.
   *
   * @param  User  $user
   * @param  Post  $post
   * @return bool
   */
  public function view(User $user, Post $post): bool
  {
    return true;
  }

  /**
   * Determine whether the user can create a post.
   *
   * @param  User  $user
   * @return bool
   */
  public function create(User $user): bool
  {
    return $user !== null;
  }

  /**
   * Determine whether the user can update a specific post.
   *
   * @param  User  $user
   * @param  Post  $post
   * @return bool
   */
  public function update(User $user, Post $post): bool
  {
    return $user->id === $post->user_id || $user->isAdmin();
  }

  /**
   * Determine whether the user can delete a specific post.
   *
   * @param  User  $user
   * @param  Post  $post
   * @return bool
   */
  public function delete(User $user, Post $post): bool
  {
    return $user->id === $post->user_id || $user->isAdmin();
  }
}
