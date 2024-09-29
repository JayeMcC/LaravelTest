<?php

namespace App\Http\Controllers\API\Content;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
  use AuthorizesRequests;

  /**
   * View a user's profile along with their posts.
   *
   * @param  int  $id
   * @return JsonResponse
   */
  public function show(int $id): JsonResponse
  {
    $user = User::findOrFail($id);

    // Retrieve the posts for the specific user, paginated
    $posts = $user->posts()->paginate(10);

    return response()->json([
      'user' => $user,
      'posts' => $posts,
    ], 200);
  }

  /**
   * Update a user's profile (only for the user themselves or an admin).
   *
   * @param  Request  $request
   * @param  User  $user
   * @return JsonResponse
   */
  public function update(Request $request, User $user): JsonResponse
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

    return response()->json([
      'message' => 'Profile updated successfully',
      'user' => $user,
    ], 200);
  }

  /**
   * Delete a user's account (only for the user themselves or an admin).
   *
   * @param  User  $user
   * @return JsonResponse
   */
  public function destroy(User $user): JsonResponse
  {
    $this->authorize('delete', $user);

    $user->delete();

    return response()->json(['message' => 'User deleted successfully'], 204);
  }

  /**
   * List all users.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse
  {
    $this->authorize('viewAny', User::class);

    $users = User::paginate(10);

    return response()->json($users, 200);
  }
}
