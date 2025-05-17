<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check() || auth()->user()->role_id != $role) {
            abort(403, 'Yetkisiz erişim.');
        }

        return $next($request);
    }
} 