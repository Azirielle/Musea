<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Don't check on Admin Port or Admin Routes
        if ($request->getPort() == 8001 || $request->is('admin/*') || $request->is('admin-login')) {
            return $next($request);
        }

        $maintenanceSetting = \App\Models\Setting::where('key', 'site_maintenance')->first();
        $isMaintenanceOn = $maintenanceSetting && ($maintenanceSetting->value == '1' || $maintenanceSetting->value === true || $maintenanceSetting->value === 'true');

        // If maintenance is ON and we are NOT on the maintenance page, redirect to it
        if ($isMaintenanceOn && !$request->is('maintenance')) {
            return redirect()->route('maintenance');
        }

        // If maintenance is OFF and we ARE on the maintenance page, redirect to home
        if (!$isMaintenanceOn && $request->is('maintenance')) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
