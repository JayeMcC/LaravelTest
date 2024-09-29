<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
  /**
   * Log in a user and return a success message with the user data.
   *
   * @param  Request  $request
   * @return JsonResponse
   */
  public function login(Request $request): JsonResponse
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required|string',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
      return response()->json(['error' => 'Invalid credentials'], 401);
    }

    Auth::login($user);

    return response()->json(['message' => 'Logged in successfully', 'user' => $user], 200);
  }

  /**
   * Log out the authenticated user and revoke their token.
   *
   * @param  Request  $request
   * @return JsonResponse
   */
  public function logout(Request $request): JsonResponse
  {
    // Revoke the token that was used to authenticate the current request
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Logged out successfully'], 200);
  }
}
