<?php

namespace Modules\User\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\CrudService;
use App\Helpers\OtpService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Modules\User\app\Http\Requests\ResetPasswordRequest;
use Modules\User\app\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ResetPasswordController extends Controller
{
    public function __construct()
    {
        $this->userModel = new CrudService(User::class);
    }
    public function resetLinkEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->only('username_email'), [
                'username_email' =>  ['required', 'string','regex:/^[^<>\/\#\$%&\*\(\)_!#]*$/'],
            ]);
            if ($validator->fails()) {
                return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Validation Errors', $validator->errors());
            }
            $loginIdentifier = $request->input('username_email');
            $type = filter_var($loginIdentifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $currentUser = $this->userModel->getByAttribute($type,$loginIdentifier);

            if ($currentUser) {
                OtpService::generate($currentUser);
                $verificationToken = JWTAuth::fromUser($currentUser);

                return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'email is correct .', ['token' => $verificationToken]);
            }else{
                return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Email not found');
            }
        }catch (\Exception $exception){
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $exception->getMessage());
        }

    }
    public function resetPassword(Request $request)
    {
    try {
        $validator = Validator::make($request->only('password'), [
            'password' => ['required', 'regex:/^[^<>]*$/', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(), Password::defaults()],
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Validation Errors', $validator->errors());
        }

        $currentUser = Auth::user();

        if ($currentUser) {
           $currentUser->update(['password' => $request->password]);
            return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Password updated Successfully', []);
        } else {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNAUTHORIZED, 'User not authenticated');
        }
    } catch (\Exception $exception) {
        return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $exception->getMessage());
    }
    }

}
