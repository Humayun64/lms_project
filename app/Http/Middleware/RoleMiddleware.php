<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Allow access only if the logged-in user has one of the given roles.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Not logged in? Send to login.
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Logged in but wrong role? Block with 403.
        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}