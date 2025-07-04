<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PimpinanMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->role === 'pimpinan' || Auth::user()->role === 'bendahara')) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses untuk fitur ini.');
    }
}