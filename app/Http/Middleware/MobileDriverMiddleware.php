<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MobileDriverMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('mobile.login');
        }

        if (!auth()->user()->hasRole('driver')) {
            abort(403, 'Acesso restrito a motoristas.');
        }

        return $next($request);
    }
}
