<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        // Fetch the setting from the database
        $maintenance = Setting::where('key', 'maintenance_mode')->value('value');
        // If Maintenance Mode is ON ('1')
        if ($maintenance == '1') {
            // Allow access ONLY to the admin panel and login/logout routes
            if (!$request->is('admin*') && !$request->is('login') && !$request->is('logout')) {
                // You can replace this with a beautiful maintenance Blade view later
                abort(503, 'BloodShare KH is currently undergoing scheduled maintenance. Please check back shortly.');
            }
        }

        return $next($request);
    }
}