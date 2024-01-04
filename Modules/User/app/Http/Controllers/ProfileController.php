<?php

namespace Modules\User\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Modules\User\app\Http\Requests\ProfileRequest;
use Modules\User\app\Http\Requests\ResetPasswordRequest;
use Modules\User\app\Http\Requests\UpdatePasswordRequest;
use Modules\User\app\Models\User;
use Modules\User\app\Resources\ProfileResource;
use Modules\User\app\Resources\RegisterResource;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the user profiles.
     *
     * @return JsonResponse
     */
    public function index()
    {

        $profile = auth()->user();

        if (is_null($profile)) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'No user data found.');
        }

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

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        if (!Hash::check($data['current_password'], $user->password)) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Current password does not match.');
        }

        $user->password = $data['new_password'];
        $user->save();

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Password updated successfully');
    }
//    public function resetLinkEmail(Request $request)
//    {
//        $validator = Validator::make($request->all(), [
//            'username_email' =>  ['required', 'string','regex:/^[^<>\/\#\$%&\*\(\)_!#]*$/'],
//        ]);
//
//        if ($validator->fails()) {
//            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Validation Errors', $validator->errors());
//        }
//        $loginIdentifier = $request->input('username_email');
//        $type = filter_var($loginIdentifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
//
//
//        $user = User::where($type, $loginIdentifier)->first();
//
//        if ($user) {
//
//            $verificationToken = JWTAuth::fromUser($user);
//
//            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'email is correct .', ['token' => $verificationToken]);
//        }
//
//        return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'Email not found', []);
//    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $loginIdentifier = $request->input('username_email');
        $type = filter_var($loginIdentifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $currentUser = User::where($type, $loginIdentifier)->first();
        if ($currentUser) {
            $currentUser->update(['password' => $request->password]);

            return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Password updated Successfully', []);
        }

        return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY,'Username or email is invalid', []);
    }



}
