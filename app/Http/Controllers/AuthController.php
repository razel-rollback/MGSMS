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
                'email'    => 'required|email',
                'password' => 'required|min:6',
                'role'     => 'required|in:Admin,Inventory,Production', // match DB values
            ]);

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = Auth::user();

                // Validate selected role against user's actual role
                if ($user->role->role_name !== $request->role) {
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Selected role does not match your account.');
                }

                // Role-based redirects
                switch ($user->role->role_name) {
                    case 'Admin':
                        return redirect()->intended('/admin/dashboard');
                    case 'Inventory':
                        return redirect()->intended('/inventory/dashboard');
                    case 'Production':
                        return redirect()->intended('/Productionstaff/stock_request-index');
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