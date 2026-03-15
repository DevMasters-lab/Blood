<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $supportedLocales = ['en', 'km'];
        $defaultLocale = config('app.locale', 'en');

        $sessionKey = $request->is('admin*') ? 'admin_locale' : 'locale';
        $sessionLocale = $request->session()->get($sessionKey);
        $locale = in_array($sessionLocale, $supportedLocales, true) ? $sessionLocale : $defaultLocale;

        App::setLocale($locale);

        return $next($request);
    }
}
