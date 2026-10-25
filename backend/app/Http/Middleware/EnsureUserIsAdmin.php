<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Runs AFTER `auth:admin` (see routes/admin.php) — that middleware proves
 * the request carries a valid token for *some* user; this one proves that
 * user is actually an admin, not just any authenticated customer.
 *
 * Two checks, both must pass:
 *   1. role === 'admin'
 *   2. is_active === true (a deactivated admin's existing tokens stop working immediately)
 *
 * Register the alias in bootstrap/app.php:
 *   $middleware->alias(['admin' => \App\Http\Middleware\EnsureUserIsAdmin::class]);
 *
 * Then guard the whole admin route group with both:
 * Route::middleware(['auth:admin', 'admin'])->group(...)
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('admin')->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'errors' => null,
            ], 401);
        }

        if ($user->role !== 'admin' || ! $user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'This action is unauthorized.',
                'errors' => null,
            ], 403);
        }

        return $next($request);
    }
}
