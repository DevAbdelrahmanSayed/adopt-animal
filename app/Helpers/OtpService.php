<?php

namespace App\Helpers;

use App\Mail\OtpVerification;
use Illuminate\Support\Facades\Mail;
use function PHPUnit\Framework\isFalse;

class OtpService
{

    public static function generate($user)
    {
        $otp = $user->otp()->first();
        if($otp){
            $otp->update([
                'otp_code' => rand(1000, 9999),
                'otp_expires_at' => now()->addMinutes(5),
            ]);
            Mail::to($user->email)->send(new OtpVerification( $user->username, $otp->otp_code, $otp->otp_expires_at));
        }

    }

    public static function verify($user, $otp)
    {


        if ($user && now()->lt($user->otp_expires_at) && $otp == $user->otp_code) {
            $user->is_otp_verified = true ;
            $user->save();
            $user->update(['otp_code' => null]);
            return true;
        }

        return false;
    }


}
