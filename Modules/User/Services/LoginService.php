<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\Auth;
use Modules\User\Dto\UserDto;

class LoginService
{
    public function store(UserDto $requestData)
    {
        $credentials = [
            'password' => $requestData->password,
        ];
        $loginIdentifier = $requestData->usernameEmail;
        $type = filter_var($loginIdentifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials[$type] = $loginIdentifier;

        if ($token = Auth::attempt($credentials)) {
            $user = Auth::user();
            $user['token'] = $token;

            return $user;
        }

        return null;
    }
}
