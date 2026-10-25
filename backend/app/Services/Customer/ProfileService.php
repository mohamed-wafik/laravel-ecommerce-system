<?php

namespace App\Services\Customer;

use App\Models\User;
use App\Services\CloudinaryService;
use App\Services\Concerns\HashesPassword;
use App\Services\Concerns\ReplacesCloudinaryImage;
use App\Services\OtpService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Self-service account management — a customer acting on their OWN
 * record. Deliberately separate from Admin\UserService (which acts on
 * *any* user and needs role/is_active fields, pagination, filters, etc.)
 * even though both end up touching the same `users` table: different
 * callers, different allowed fields, different authorization story. The
 * actual avatar-upload and password-hashing logic is still shared via the
 * same traits Admin\UserService uses, so that part isn't duplicated.
 */
class ProfileService
{
    use HashesPassword, ReplacesCloudinaryImage;

    public function __construct(protected CloudinaryService $cloudinary,protected OtpService $otp) {}

    public function update(User $user, array $data): User
    {
        $data = $this->replaceImage(
            $data, 
            'avatar', 
            'avatar_url', 
            'avatar_public_id', 
            'avatars', 
            $user->avatar_public_id
        );

        $user->update($data);

        return $user->fresh();
    }

    /**
     * Verifies the current password before allowing a change — unlike
     * admin resetting someone else's password, self-service change proves
     * account ownership beyond just having a valid session token.
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (! Hash::check($currentPassword, $user->password_hash)) {
            throw ValidationException::withMessages(['current_password' => 'Current password is incorrect.']);
        }

        $user->update($this->hashPassword(['password' => $newPassword]));

        // Every OTHER session is signed out; the one making this request stays valid.
        $user->tokens()->where('id', '!=', $user->currentAccessToken()?->id)->delete();
    }

    public function changeEmail(string $email, User $user, string $password): void
    {
        Log::info("Changing email for user {$user->id} to {$email}");

        if (! Hash::check($password, $user->password_hash)) {
            throw ValidationException::withMessages(['password' => 'Password is incorrect.']);
        }

        if (User::where('email', $email)->exists()) {
            throw ValidationException::withMessages(['email' => 'Email is already in use.']);
        }

        $this->otp->generate($email, 'register', $user->id);
    }
    public function verifyEmailChange(string $email, string $code, User $user): User {
        $this->otp->verify($email, 'register', $code);
        $user->update(['email' => $email, 'email_verified_at' => now()]);
        return $user->fresh();
    }
    public function removeAvatar(User $user): void
    {
        if ($user->avatar_public_id) {
            $this->cloudinary->destroy($user->avatar_public_id);
            $user->update(['avatar_url' => null, 'avatar_public_id' => null]);
        }
    }
    public function deactivate(User $user): void
    {
        $user->update(['is_active' => false]);
        $user->tokens()->delete();
    }
}