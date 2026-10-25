<?php

namespace App\Services\Concerns;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * UserService (admin), ProfileService (customer self-service), and
 * AuthService::register() all needed "hash `password` into
 * `password_hash`, or generate one if none was given" — one place now
 * instead of three near-identical `Hash::make(...); unset(...)` blocks.
 */
trait HashesPassword
{
    /**
     * @param  array  $data  payload containing a plaintext `password` key (optional on update)
     * @param  bool  $required  create() needs a password (or a random one); update() only touches it if present
     */
    protected function hashPassword(array $data, bool $required = false): array
    {
        if (! empty($data['password'])) {
            $data['password_hash'] = Hash::make($data['password']);
        } elseif ($required) {
            $data['password_hash'] = Hash::make(Str::random(32));
        }

        unset($data['password'], $data['password_confirmation']);

        return $data;
    }
}
