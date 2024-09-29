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
   * Return a specific user in JSON format.
   *
   * @param  User  $user
   * @return JsonResponse
   */
  public function show(User $user): JsonResponse
  {
    return response()->json($user, 200);
  }

  /**
   * List all users.
   * # Implicitly requested by part 4, Features
   * # "Implement pagination for list endpoints (posts, comments, users)"
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
