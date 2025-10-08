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
        // validate input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Role-based redirects
            switch ($user->role->role_name) {
                case 'Admin':
                    return redirect()->intended('/admin/dashboard');
                case 'Inventory':
                    return redirect()->intended('/inventory/dashboard');
                case 'Production':
            // Production users go to Stock Request page
                    return redirect()->intended('/production/stock-request');
                default:
                    return redirect()->intended('/home');
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