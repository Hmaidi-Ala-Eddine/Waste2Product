<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // If not authenticated, redirect to admin sign-in
        if (! $user) {
            return redirect()->route('admin.sign-in');
        }

        // Only allow users with role 'admin'
        if (isset($user->role) && $user->role === 'admin') {
            return $next($request);
        }

        // Not authorized for admin area
        abort(403, 'Unauthorized');
    }
}
