<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Kullanıcı giriş yapmışsa, profilindeki dili kullan
            App::setLocale(Auth::user()->language);
        } else {
            // Session'da dil yoksa, tarayıcı dilini algıla ve session'a kaydet
            if (!session()->has('locale')) {
                $browserLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
                $supported = ['tr', 'en'];
                $locale = in_array($browserLocale, $supported) ? $browserLocale : config('app.locale');
                session(['locale' => $locale]);
            }
            App::setLocale(session('locale', config('app.locale')));
        }
        return $next($request);
    }
}
