<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminPort
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If we are on port 8001, we treat this as the Admin Interface
        if ($request->getPort() == 8001) {
            // Redirect root URL to admin dashboard
            if ($request->is('/')) {
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}
