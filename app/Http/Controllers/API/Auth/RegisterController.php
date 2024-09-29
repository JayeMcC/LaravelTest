<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use App\Jobs\SendWelcomeEmail;

class RegisterController extends Controller
{
  /**
   * Register a new user and return a success message with the user data.
   *
   * @param  RegisterRequest  $request
   * @return JsonResponse
   */
  public function register(RegisterRequest $request): JsonResponse
  {
    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    SendWelcomeEmail::dispatch($user);

    return response()->json(['message' => 'Registration successful', 'user' => $user], 201);
  }
}
