<?php

namespace Modules\User\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Modules\User\app\Http\Requests\ProfileRequest;
use Modules\User\app\Http\Requests\UpdatePasswordRequest;
use Modules\User\app\Resources\ProfileResource;
use Modules\User\Dto\UserDto;
use Modules\User\Services\ProfileService;

class ProfileController extends Controller
{
    public function __construct(protected ProfileService $profileService)
    {
    }

    public function index(): JsonResponse
    {
        try {
            $userId = Auth::id();
            $profile = Cache::remember("user.profile.{$userId}", now()->addHour(1), function () {
                return new ProfileResource(Auth::user());
            });

            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'User profile retrieved successfully', new ProfileResource($profile));
        } catch (\Throwable $e) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Error retrieving user profile.');
        }
    }

    public function update(ProfileRequest $request): JsonResponse
    {
        try {

            $requestData = UserDto::fromProfileRequest($request);
            $responseData = $this->profileService->update(Auth::user(), $requestData);

            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'User profile updated successfully', new ProfileResource($responseData));
        } catch (\Exception $exception) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $exception->getMessage());
        }
    }

    public function changePassword(UpdatePasswordRequest $request): JsonResponse
    {
        try {
            $requestData = UserDto::fromUpdatePasswordRequest($request);
            if ($this->profileService->changePassword(Auth::user(), $requestData)) {

                return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'password updated successfully');
            } else {
                return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'old password not correct');
            }

        } catch (\Exception $exception) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $exception->getMessage());
        }

    }
}
