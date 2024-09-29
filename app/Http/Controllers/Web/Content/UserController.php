<?php

namespace App\Http\Controllers\Web\Content;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
  use AuthorizesRequests;

  /**
   * View a user's profile with their posts.
   *
   * @param  int  $id
   * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
   */
  public function show(int $id)
  {
    $user = User::findOrFail($id);

    // Retrieve the posts for the specific user, paginated
    $posts = $user->posts()->paginate(10);

    // Return the view with the user's profile and their posts
    return view('users.posts', compact('user', 'posts'));
  }

  /**
   * Display a list of posts for a specific user by ID.
   *
   * @param  int  $id
   * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
   */
  public function userPosts(int $id)
  {
    $user = User::findOrFail($id);
    $posts = $user->posts()->paginate(10);

    return view('users.posts', compact('user', 'posts'));
  }

  /**
   * Update a user's profile (only for the user themselves or an admin).
   *
   * @param  Request  $request
   * @param  User  $user
   * @return RedirectResponse
   */
  public function update(Request $request, User $user): RedirectResponse
  {
    $this->authorize('update', $user);

    $validatedData = $request->validate([
      'name' => 'sometimes|required|string|max:255',
      'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
      'password' => 'sometimes|required|string|min:8|confirmed',
    ]);

    if (isset($validatedData['password'])) {
      $validatedData['password'] = Hash::make($validatedData['password']);
    }

    $user->update($validatedData);

    return redirect()->route('users.show', $user->id)
      ->with('success', 'Profile updated successfully.');
  }

  /**
   * List all users (admin only).
   *
   * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
   */
  public function index()
  {
    $this->authorize('viewAny', User::class);

    $users = User::paginate(10);

    return view('users.index', compact('users'));
  }
}
