<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            // Block users whose account is not active
            if (Auth::user()->status === 'blocked') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Your account has been blocked. Please contact support.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return $this->redirectByRole();
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
