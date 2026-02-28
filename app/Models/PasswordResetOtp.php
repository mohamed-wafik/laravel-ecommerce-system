<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class PasswordResetOtp extends Model
{
    protected $fillable = [
        'email',
        'otp',
        'token',
        'attempts',
        'verified',
        'expires_at',
    ];

    protected $casts = [
        'verified'   => 'boolean',
        'expires_at' => 'datetime',
    ];

    protected $hidden = ['otp', 'token'];

    // ── helpers ─────────────────────────────────────────────────────────────

    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }

    public function isMaxAttemptsReached(): bool
    {
        return $this->attempts >= 5; // max 5 wrong tries
    }

    public function verifyOtp(string $plain): bool
    {
        return Hash::check($plain, $this->otp);
    }
}