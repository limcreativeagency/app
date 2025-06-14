<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

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
        // Session'da dil ayarı yoksa tarayıcı dilini kontrol et
        if (!session()->has('locale')) {
            $browserLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
            if (in_array($browserLocale, ['tr', 'en'])) {
                session(['locale' => $browserLocale]);
            } else {
                session(['locale' => config('app.locale', 'tr')]);
            }
        }
        
        $locale = session('locale');
        
        // Desteklenen dilleri kontrol et
        if (!in_array($locale, ['tr', 'en'])) {
            $locale = config('app.locale', 'tr');
        }
        
        // Dili ayarla
        App::setLocale($locale);
        
        // Debug için log
        Log::debug('Current locale: ' . App::getLocale());
        
        return $next($request);
    }
}
