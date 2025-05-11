<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocaleFromBrowser
{
    public function handle($request, Closure $next)
    {
        if (!session()->has('locale')) {
            $locale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
            if (!in_array($locale, ['tr', 'en'])) {
                $locale = 'en'; // default
            }
            session(['locale' => $locale]);
        }
        App::setLocale(session('locale'));
        return $next($request);
    }
} 