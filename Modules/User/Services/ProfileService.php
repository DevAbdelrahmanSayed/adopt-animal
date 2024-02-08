<?php

namespace Modules\User\Services;

use App\Helpers\MediaService;
use Illuminate\Support\Facades\Hash;
use Modules\User\app\Models\User;

class ProfileService
{
    public function __construct()
    {
        $this->userModel = new MediaService(User::class);
    }

    public function changePassword($user, $requestData)
    {
        if (Hash::check($requestData->old_password, $user->password)) {
            $user->update(['password' => $requestData->new_password]);
            return true;
        } else {
            return false;
        }
    }


    public function update($user, $requestData)
    {

        $currentProfile = $user->profile;

        if (isset($requestData['profile'])){
            $photoUrl = $requestData['profile'];
            $pathPhoto = $this->userModel->storePhoto($user->id, $photoUrl);
            $requestData['profile'] = $pathPhoto;
        }
        if ($user->profile) {

            $this->userModel->deleteOldPhoto($user->id);
        }
        $user->update($requestData);
        return $user;
    }


}
