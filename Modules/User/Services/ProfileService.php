<?php

namespace Modules\User\Services;

use App\Helpers\MediaService;
use Illuminate\Support\Facades\Hash;
use Modules\User\app\Models\User;
use Modules\User\Dto\UserDto;

class ProfileService
{
    public function changePassword(user $user, UserDto $userDto)
    {
        if (Hash::check($userDto->password, $user->password)) {
            $user->update(['password' => $userDto->newPassword]);

            return true;
        } else {
            return false;
        }
    }

    public function update(user $user, UserDto $userDto): User
    {
        // Filter out null values from the DTO array
        $updatedData = array_filter($userDto->toArrayProfile());

        $user->update($updatedData);
        if (! is_null($userDto->profile)) {
            $pathPhoto = MediaService::storePhoto($user->id, $userDto->profile);
            $user->profile = $pathPhoto;
            $user->save();
        }

        return $user;
    }
}
