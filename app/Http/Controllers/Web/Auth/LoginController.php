<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
  /**
   * Show the login form.
   *
   * @return \Illuminate\Http\Response
   */
  public function showLoginForm()
  {
    return response()->view('auth.login');
  }

  /**
   * Log in a user and redirect to the home page.
   *
   * @param  Request  $request
   * @return RedirectResponse
   */
  public function login(Request $request): RedirectResponse
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required|string',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
      return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
    }

    Auth::login($user);

    # Generate Sanctum Token for easy swagger access
    $token = $user->createToken('web_token')->plainTextToken;

    # Store the token in session to make it available for the client-side JavaScript
    session(['token' => $token]);

    return redirect()->route('home');
  }

  /**
   * Log out the authenticated user and redirect to the welcome page.
   *
   * @param  Request  $request
   * @return RedirectResponse
   */
  public function logout(Request $request): RedirectResponse
  {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
  }
}
