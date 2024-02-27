<?php

namespace Modules\User\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Modules\User\app\Http\Requests\ResetLinkEmailRequest;
use Modules\User\Dto\UserDto;
use Modules\User\Services\ResetPasswordService;

class ResetPasswordController extends Controller
{
    public function __construct(protected ResetPasswordService $resetPasswordService)
    {
    }

    public function resetLinkEmail(ResetLinkEmailRequest $request)
    {
        try {
            $requestData = UserDto::resetLinkRequest($request);
            $responseData = $this->resetPasswordService->resetLinkEmail($requestData);

            if ($responseData) {

                return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'email is correct .', ['token' => $responseData]);
            } else {
                return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Email not found');
            }
        } catch (\Exception $exception) {
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
                $validatedData = $validator->validated();
                $requestData = UserDto::resetPasswordRequest($validatedData['password']);
                $this->resetPasswordService->resetPassword($currentUser, $requestData);

                return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Password updated Successfully', []);
            } else {
                return ApiResponse::sendResponse(JsonResponse::HTTP_UNAUTHORIZED, 'User not authenticated');
            }
        } catch (\Exception $exception) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $exception->getMessage());
        }
    }

}
