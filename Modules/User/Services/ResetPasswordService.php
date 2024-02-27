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
        $currentUser = User::where('email', $userDto->Email)->first();
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
