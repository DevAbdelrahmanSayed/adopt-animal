<?php

namespace Modules\User\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Post\app\Resources\PostsResource;
use Modules\User\app\Http\Requests\ProfileRequest;
use Modules\User\app\Http\Requests\UpdatePasswordRequest;
use Modules\User\app\Models\User;
use Modules\User\app\Resources\ProfileResource;
use Modules\User\app\Resources\RegisterResource;

class ProfileController extends Controller
{
    /**
     * Display a listing of the user profiles.
     *
     * @return JsonResponse
     */
    public function index()
    {
        // Get the authenticated user's profile
        $profile = auth()->user();

        // Check if the user profile is null
        if (is_null($profile)) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'No user data found.');
        }

        // Return the user data
        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'User data retrieved successfully', new ProfileResource($profile));
    }


    /**
     * Update the specified user profile.
     *
     * @param ProfileRequest $request
     * @return JsonResponse
     */
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $user->update($data);

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'User profile updated successfully', new RegisterResource($user));
    }
    /**
     * Update the password for the authenticated user.
     *
     * @param UpdatePasswordRequest $request
     * @return JsonResponse
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        if (!Hash::check($data['current_password'], $user->password)) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Current password does not match.');
        }

        $user->password =$data['new_password'];
        $user->save();

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Password updated successfully');
    }


}
