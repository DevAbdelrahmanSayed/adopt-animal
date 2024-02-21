<?php

namespace Modules\User\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\User\app\Http\Requests\OtpRequest;
use Modules\User\Dto\OtpDto;
use Modules\User\Services\OtpService;

class OtpController extends Controller
{
    public function __construct(protected OtpService $otpService)
    {
    }

    public function verify(OtpRequest $request)
    {
        try {
            $responseData = OtpDto::fromOtpRequest($request);

            if ($this->otpService->verify(Auth::user(), $responseData)) {
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
            $responseData = $this->otpService->resendOtp(Auth::user());
            if ($responseData === false) {
                return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'User not found');
            }

            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'A new OTP has been sent to your email.');

        } catch (\Exception $exception) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $exception->getMessage());
        }
    }
}
