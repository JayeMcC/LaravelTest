<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
  /**
   * Show the registration form.
   *
   * @return \Illuminate\Http\Response
   */
  public function showRegistrationForm()
  {
    return response()->view('auth.register');
  }

  /**
   * Register a new user and log them in.
   *
   * @param  RegisterRequest  $request
   * @return RedirectResponse
   */
  public function register(RegisterRequest $request): RedirectResponse
  {
    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    Auth::login($user);

    return redirect()->route('home');
  }
}
