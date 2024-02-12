<?php

namespace Modules\User\Services;

use App\Helpers\Otp;
use Illuminate\Support\Facades\Auth;
use Modules\User\app\Models\User;
use Modules\User\Dto\OtpDto;

class OtpService
{
    public function verify($user, OtpDto $otpDto)
    {
        return $user ? Otp::verify(Auth::user(), $otpDto->otpCode) : false;

    }

    public function resendOtp(User $user)
    {
            if ($user->otp_code) {
                $user->update(['otp_code' => null]);
                return Otp::generate($user);

            }
        return false;
    }
}
