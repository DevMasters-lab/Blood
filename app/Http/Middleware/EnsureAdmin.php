<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('admin');

        $hasAdminRole = $user
            ? $user->roles()
                ->where('guard_name', 'web')
                ->where('name', '!=', 'user')
                ->exists()
            : false;

        if (! $user || ($user->usertype !== 'admin' && ! $hasAdminRole)) {
            abort(403, 'Unauthorized admin access.');
        }

        return $next($request);
    }
}
