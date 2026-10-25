<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards every guest-cart route except the one that mints the token itself.
 * Keeps the "missing token" case in the same success/error envelope as
 * everything else, instead of a raw abort().
 */
class EnsureGuestToken
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->header('X-Guest-Token')) {
            return response()->json([
                'success' => false,
                'message' => 'Missing X-Guest-Token header. Call POST /guest/cart/token first.',
                'errors' => null,
            ], 422);
        }

        return $next($request);
    }
}
