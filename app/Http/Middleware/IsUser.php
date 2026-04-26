<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsUser
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isUser()) {
            abort(403, 'غير مصرح لك بالدخول');
        }

        return $next($request);
    }
}
