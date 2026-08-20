<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle a login attempt
    public function login(Request $request)
    {
        // Validate the input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to log the user in
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Send the user to the correct place based on role
            return $this->redirectByRole();
        }

        // If login failed, go back with an error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Decide where to send the user after login
    protected function redirectByRole()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin'        => redirect()->route('admin.dashboard'),
            'instructor'   => redirect()->route('instructor.dashboard'),
            'organization' => redirect()->route('organization.dashboard'),
            default        => redirect()->route('student.dashboard'),
        };
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}