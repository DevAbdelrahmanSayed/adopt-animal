<?php

namespace Modules\User\Services;

use Modules\User\app\Models\User;
use Modules\User\Dto\UserDto;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class RegisterService
{
    public function store(UserDto $userDto)
    {
        $userData = User::create($userDto->toArrayRegister());
        $userData['token'] = JWTAuth::fromUser($userData);

        return $userData;

    }
}
