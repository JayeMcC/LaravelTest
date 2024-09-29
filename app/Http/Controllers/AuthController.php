<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the registration form.
     *
     * @return Response
     */
    public function showRegistrationForm(): Response
    {
        return response()->view('auth.register');
    }

    /**
     * Show the login form.
     *
     * @return Response
     */
    public function showLoginForm(): Response
    {
        return response()->view('auth.login');
    }

    /**
     * Register a new user.
     *
     * @param  RegisterRequest  $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Log in the user
        Auth::login($user);

        // Redirect to home page after registration
        return redirect()->route('home');
    }

    /**
     * Log in a user and issue a token.
     *
     * @param  Request  $request
     * @return JsonResponse|RedirectResponse
     */
    public function login(Request $request)
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

        return redirect()->route('home');
    }

    /**
     * Log out the authenticated user and revoke their token.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Get a specific user's profile by ID.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function getUser(int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        return response()->json($user, 200);
    }
}
