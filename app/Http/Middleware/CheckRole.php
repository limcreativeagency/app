<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $roles)
    {
        $roleArray = explode(',', $roles);
        if (!auth()->check() || !in_array(auth()->user()->role_id, $roleArray)) {
            abort(403, 'Yetkisiz erişim.');
        }
        return $next($request);
    }
} 