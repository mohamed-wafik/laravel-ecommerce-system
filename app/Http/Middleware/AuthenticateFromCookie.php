<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateFromCookie
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->bearerToken() && $request->hasCookie('auth_token')) {
            $token = $request->cookie('auth_token');
            
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        return $next($request);
    }
}