<?php

namespace Modules\User\Services;

use App\Helpers\Otp;
use Modules\User\app\Models\User;
use Modules\User\Dto\UserDto;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ResetPasswordService
{
    public function resetLinkEmail(UserDto $userDto)
    {
        $loginIdentifier = $userDto->usernameEmail;
        $type = filter_var($loginIdentifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $currentUser = User::where($type, $loginIdentifier)->first();
        if ($currentUser) {
            Otp::generate($currentUser);

            return JWTAuth::fromUser($currentUser);
        }

        return false;
    }

    public function resetPassword(User $user, UserDto $userDto): bool
    {
        return $user ? $user->update(['password' => $userDto->password]) : false;

    }
}
