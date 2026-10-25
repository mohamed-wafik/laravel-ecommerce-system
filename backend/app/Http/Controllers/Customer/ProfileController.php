<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ChangePasswordRequest;
use App\Http\Requests\Customer\UpdateProfileRequest;
use App\Services\Customer\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function __construct(protected ProfileService $profile) {}

    public function show(Request $request)
    {
        return $this->success($request->user());
    }

    public function update(UpdateProfileRequest $request)
    {
        return $this->success($this->profile->update($request->user(), $request->validated()), 'Profile updated');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $this->profile->changePassword($request->user(), $request->current_password, $request->new_password);
        } catch (ValidationException $e) {
            return $this->validationError($e->errors(), $e->getMessage());
        }

        return $this->noContentSuccess('Password changed');
    }


    public function changeEmail(Request $request)
    {
        $validated = $request->validate([
            'new_email' => 'required|email',
            "password" => 'required|string',
        ]);

        try {
            $this->profile->changeEmail($validated['new_email'], $request->user(), $validated['password']);
        } catch (ValidationException $e) {
            return $this->validationError($e->errors(), $e->getMessage());
        }

        return $this->noContentSuccess('Verification code sent to new email');
    }
    public function verifyEmailChange(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        try {
            $user = $this->profile->verifyEmailChange($validated['email'], $validated['code'], $request->user());
        } catch (ValidationException $e) {
            return $this->validationError($e->errors(), $e->getMessage());
        }

        return $this->success($user, 'Email changed successfully');
    }
    public function removeAvatar(Request $request)
    {
         $this->profile->removeAvatar($request->user());

        return $this->noContentSuccess('Avatar removed');
    }
    public function destroy(Request $request)
    {
        $this->profile->deactivate($request->user());

        return $this->noContentSuccess('Account deactivated');
    }
}