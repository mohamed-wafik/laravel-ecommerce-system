<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Alternative to the `admin` middleware for finer-grained checks
        // inside controllers/blade/policies, e.g. `Gate::authorize('admin')`
        // or `@can('admin')`. The middleware is what actually protects the
        // routes; this exists for spot-checks elsewhere in the app.
        Gate::define('admin', fn (User $user) => $user->role === 'admin' && $user->is_active);

        $this->configureRateLimiters();
    }

    /**
     * Named limiters applied via `throttle:<name>` in routes/auth.php and
     * routes/guest.php. Keyed by identifier+IP (not IP alone) where it
     * matters, so one attacker IP can't lock out every other user sharing
     * it (NAT/office wifi/mobile carrier), while still capping abuse
     * against any single account.
     */
    protected function configureRateLimiters(): void
    {
        // Register/login: prevent credential stuffing & brute force.
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        // OTP send/verify: the sensitive one — codes are only 6 digits,
        // so verification attempts need a tight cap independent of the
        // 5-attempts-per-code cap already enforced in OtpService.
        RateLimiter::for('otp', function (Request $request) {
            $identifier = $request->input('email', $request->ip());

            return [
                Limit::perMinute(3)->by('otp-per-identifier:'.$identifier),
                Limit::perMinute(20)->by('otp-per-ip:'.$request->ip()),
            ];
        });

        // Refresh: token-holder already proved something, but still capped
        // so a compromised refresh token can't be hammered for new access tokens.
        RateLimiter::for('refresh', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });

        // Public catalog browsing: generous, but stops scraping/DOS.
        RateLimiter::for('guest', function (Request $request) {
            return Limit::perMinute(120)->by($request->header('X-Guest-Token', $request->ip()));
        });

        // Checkout creates a real Stripe PaymentIntent per call — cap hard
        // per user regardless of idempotency_key reuse protecting against duplicates.
        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinute(6)->by($request->user()?->id ?: $request->ip());
        });

        // Public contact form: free-text + email, no auth — a classic
        // spam target. Capped per submitted email AND per IP, same
        // dual-limit reasoning as 'otp' above.
        RateLimiter::for('contact', function (Request $request) {
            $identifier = $request->input('email', $request->ip());

            return [
                Limit::perMinutes(10, 3)->by('contact-per-identifier:'.$identifier),
                Limit::perMinutes(10, 10)->by('contact-per-ip:'.$request->ip()),
            ];
        });
    }
}