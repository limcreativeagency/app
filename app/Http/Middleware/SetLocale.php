<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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
        // Eğer kullanıcı (session) tarafından özel bir dil seçilmişse, onu kullan.
        if (session()->has('locale')) {
            App::setLocale(session('locale'));
        } else {
            // Tarayıcı dilini (Accept-Language header) al, örneğin "tr" veya "en" gibi.
            $browserLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
            // Desteklenen dilleri kontrol et (örneğin "tr" veya "en")
            if (in_array($browserLocale, ['tr', 'en'])) {
                App::setLocale($browserLocale);
            } else {
                // Desteklenmeyen bir dil ise, varsayılan (örneğin "tr") kullan.
                App::setLocale(config('app.locale'));
            }
        }
        return $next($request);
    }
}
