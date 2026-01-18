<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for Admin routes
        if ($request->is('admin', 'admin/*') || $request->getPort() == 8001) {
            return $next($request);
        }

        if (Auth::check() && Auth::user()->status !== 'active') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $route = \Illuminate\Support\Facades\Route::has('login') ? 'login' : 'admin.login';
            return redirect()->route($route)->with('error', 'Your account has been suspended.');
        }

        return $next($request);
    }
}
