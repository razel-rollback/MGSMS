<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login attempt.
     */
    public function login(Request $request)
    {
        // validate input including role
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();



            // Role-based redirects
            switch ($user->role->role_name) {
                case 'Admin':
                    return redirect()->route('dashboard');
                case 'Inventory':
                    return redirect()->route('dashboard');
                case 'Production':
                    return redirect()->route('dashboard');
                default:
                    return redirect()->route('home');
            }
        }

        // if login fails
        return back()->withErrors([
            'email' => 'Invalid credentials provided.',
        ])->withInput();
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
