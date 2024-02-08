<?php

namespace Modules\User\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\OtpService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    public function verify(Request $request)
    {
        try {
            $otpData = Validator::make($request->only('otp_code'), [
                'otp_code' => ['required', 'integer', 'digits:4'],
            ]);

            if ($otpData->fails()) {
                return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Validation Errors', $otpData->errors());
            }

            $validatedData = $otpData->validated();

            if (OtpService::verify(Auth::user(),  $validatedData['otp_code'])) {
                return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Your account has been verified successfully.', ['is_verified' => true]);
            } else {
                return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'There is an error in OTP');
            }
        } catch (\Exception $exception) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $exception->getMessage());
        }
    }

    public function resendOtp()
    {
        try {
            $currentUser = Auth::user();
            if (!$currentUser) {
                return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'User not found');
            }
            $currentUser->otp()->delete();
            OtpService::generate($currentUser);

            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'A new OTP has been sent to your email.');
        } catch (\Exception $exception) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $exception->getMessage());
        }
    }



}
