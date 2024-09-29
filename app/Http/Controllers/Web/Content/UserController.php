<?php

namespace App\Http\Controllers\Web\Content;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
  use AuthorizesRequests;

  /**
   * View a user's account details (profile information only).
   *
   * @param  User  $user
   * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
   */
  public function show(User $user)
  {
    return view('users.show', compact('user'));
  }

  /**
   * Display a list of posts for a specific user.
   *
   * @param  User  $user
   * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
   */
  public function userPosts(User $user)
  {
    $posts = $user->posts()->paginate(10);

    return view('users.posts', compact('user', 'posts'));
  }
}
