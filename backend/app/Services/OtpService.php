<?php

namespace App\Services;

use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Generates and verifies one-time codes for register / login / password
 * reset. Deliberately generic on `identifier` (email today, phone is the
 * same flow later) and `type` so one service backs every OTP use case
 * instead of duplicating this logic per flow.
 */
class OtpService
{
    protected const CODE_LENGTH_MIN = 100000;

    protected const CODE_LENGTH_MAX = 999999;

    protected const EXPIRES_IN_MINUTES = 10;

    protected const MAX_ATTEMPTS = 5;

    public function generate(string $identifier, string $type, ?string $userId = null): Otp
    {
        // Only one live code per identifier+type — a fresh request invalidates
        // any earlier unused one instead of letting several stay valid at once.
        Otp::where('identifier', $identifier)->where('type', $type)->where('is_used', false)->delete();

        $code = (string) random_int(self::CODE_LENGTH_MIN, self::CODE_LENGTH_MAX);

        $otp = Otp::create([
            'user_id' => $userId,
            'identifier' => $identifier,
            'code_hash' => Hash::make($code),
            'type' => $type,
            'expires_at' => now()->addMinutes(self::EXPIRES_IN_MINUTES),
        ]);

        $this->dispatch($identifier, $type, $code);

        return $otp;
    }

    /**
     * @throws ValidationException
     */
    public function verify(string $identifier, string $type, string $code): Otp
    {
        $otp = Otp::where('identifier', $identifier)
            ->where('type', $type)
            ->where('is_used', false)
            ->latest('created_at')
            ->first();

        if (! $otp) {
            throw ValidationException::withMessages([
                'code' => 'No pending verification found for this request. Please request a new code.',
            ]);
        }

        if ($otp->isExpired()) {
            throw ValidationException::withMessages([
                'code' => 'This code has expired. Please request a new one.',
            ]);
        }

        if ($otp->attempts >= self::MAX_ATTEMPTS) {
            throw ValidationException::withMessages([
                'code' => 'Too many incorrect attempts. Please request a new code.',
            ]);
        }

        if (! Hash::check($code, $otp->code_hash)) {
            $otp->increment('attempts');

            throw ValidationException::withMessages(['code' => 'Invalid code.']);
        }

        $otp->update(['is_used' => true]);

        return $otp;
    }

    /**
     * Sends the code to the user. Swap this for a real Mail/Notification
     * class (Mail::to($identifier)->send(new OtpMail($code))) or an SMS
     * gateway for phone identifiers — logging is a placeholder so the flow
     * is testable without provider credentials.
     */
    protected function dispatch(string $identifier, string $type, string $code): void
    {
        Log::info("OTP [{$type}] for {$identifier}: {$code}");
    }
}
