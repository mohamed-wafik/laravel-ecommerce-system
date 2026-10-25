<?php

namespace App\Services;

use App\Models\User;
use App\Services\Concerns\HashesPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Customer-facing auth. Every method that succeeds by issuing tokens
 * returns the same shape:
 *   { access_token, refresh_token, user }
 *
 * Token design (large-e-commerce pattern — short-lived access token you
 * send on every request, long-lived refresh token you only send to mint a
 * new access token):
 *   - access_token:  30 min,  ability ['access-api']
 *   - refresh_token: 30 days, ability ['issue-access-token']
 * Both are Sanctum personal access tokens on the `sanctum` guard. Refresh
 * tokens are single-use — refreshing deletes the one just used and issues
 * a brand new pair (rotation), so a leaked refresh token only works once
 * before the legitimate client's next refresh silently invalidates it.
 */
class AuthService
{
    use HashesPassword;

    protected const ACCESS_TOKEN_TTL_MINUTES = 30;

    protected const REFRESH_TOKEN_TTL_DAYS = 30;

    public function __construct(protected OtpService $otps) {}

    /**
     * Step 1 of register -> verify -> tokens. No tokens issued yet —
     * the account exists but is unverified until the OTP is confirmed.
     */
    public function register(array $data): array
    {
        if (User::where('email', $data['email'])->exists()) {
            throw ValidationException::withMessages(['email' => 'This email is already registered.']);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'role' => 'customer',
            ...$this->hashPassword($data, required: true),
        ]);

        $this->otps->generate($user->email, 'register', $user->id);

        return [
            'user_id' => $user->id,
            'message' => 'Registered. A verification code was sent to your email.',
        ];
    }

    /**
     * Step 2 of register -> verify -> tokens.
     */
    public function verifyRegistration(string $email, string $code): array
    {
        $user = User::where('email', $email)->firstOrFail();

        $this->otps->verify($email, 'register', $code);

        $user->update(['email_verified_at' => now()]);

        return $this->issueTokens($user);
    }

    /**
     * If the account isn't verified yet, login does NOT issue tokens —
     * it (re)sends a verification OTP instead and the client is expected
     * to call verifyLogin() next. Verified accounts get tokens immediately.
     */
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password_hash)) {
            throw ValidationException::withMessages(['email' => 'These credentials do not match our records.']);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages(['email' => 'This account has been deactivated.']);
        }

        if (! $user->email_verified_at) {
            $this->otps->generate($user->email, 'login', $user->id);

            return [
                'verified' => false,
                'message' => 'Email not verified. A verification code was sent.',
            ];
        }

        return array_merge(['verified' => true], $this->issueTokens($user));
    }

    /**
     * Completes login for an unverified account after login() sent a code.
     */
    public function verifyLogin(string $email, string $code): array
    {
        $user = User::where('email', $email)->firstOrFail();

        $this->otps->verify($email, 'login', $code);

        if (! $user->email_verified_at) {
            $user->update(['email_verified_at' => now()]);
        }

        return $this->issueTokens($user);
    }

    /**
     * Google is a trusted identity provider, so this skips the OTP step
     * entirely — an account reached via a verified Google email is
     * considered verified immediately. Same result shape as every other
     * successful auth method: { access_token, refresh_token, user }.
     *
     * Links by `google_id` first, falling back to `email` so someone who
     * registered the normal way and later uses "Sign in with Google" on
     * the same address gets logged into their existing account instead of
     * a duplicate one — the account gets `google_id` backfilled at that point.
     */
    public function loginWithGoogle(string $googleId, string $email, string $name, ?string $avatarUrl = null): array
    {
        $user = User::where('google_id', $googleId)->orWhere('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId,
                'role' => 'customer',
                'email_verified_at' => now(),
                'avatar_url' => $avatarUrl,
                ...$this->hashPassword([], required: true), // unusable random password — set one later via reset-password
            ]);
        } elseif (! $user->google_id) {
            $user->update([
                'google_id' => $googleId,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages(['email' => 'This account has been deactivated.']);
        }

        return $this->issueTokens($user);
    }

    /**
     * Always responds the same way whether or not the email exists, so the
     * endpoint can't be used to enumerate registered accounts.
     */
    public function forgotPassword(string $email): void
    {
        $email = strtolower(trim($email));

        $user = User::where('email', $email)->first();

        if ($user) {
            $this->otps->generate(
                $email,
                'reset_password',
                $user->id
            );
        }
    }

    public function checkOtp(string $email, string $code): string
    {

        $user = User::where('email', $email)->firstOrFail();

        $this->otps->verify(
            $email,
            'reset_password',
            $code
        );

        return $user->createToken(
            'password_reset',
            ['password-reset'],
            now()->addMinutes(10)
        )->plainTextToken;
    }
    
    public function resetPassword(User $user, string $newPassword): array
    {
        $user->update(
            $this->hashPassword([
                'password' => $newPassword
            ])
        );

        $this->logoutAllDevices($user);

        return $this->issueTokens($user);
    }

    /**
     * $refreshToken is the currently-authenticated Sanctum token — the
     * caller (controller) must confirm it actually has the
     * 'issue-access-token' ability before calling this, since a plain
     * access token authenticating here should NOT be able to mint another.
     */
    public function refresh(PersonalAccessToken $refreshToken): array
    {
        return DB::transaction(function () use ($refreshToken) {
            /** @var User $user */
            $user = $refreshToken->tokenable;

            $refreshToken->delete(); // rotation: this refresh token is now dead

            return $this->issueTokens($user);
        });
    }

    public function logout(User $user, PersonalAccessToken $currentToken): void
    {
        $currentToken->delete();
    }

    public function logoutAllDevices(User $user): void
    {
        $user->tokens()->delete();
    }

    protected function issueTokens(User $user): array
    {
        $access = $user->createToken(
            'access_token',
            ['access-api'],
            now()->addMinutes(self::ACCESS_TOKEN_TTL_MINUTES)
        )->plainTextToken;

        $refresh = $user->createToken(
            'refresh_token',
            ['issue-access-token'],
            now()->addDays(self::REFRESH_TOKEN_TTL_DAYS)
        )->plainTextToken;

        return [
            'access_token' => $access,
            'refresh_token' => $refresh,
            'user' => $user->only(['id', 'name', 'email', 'phone', 'role']),
        ];
    }
}