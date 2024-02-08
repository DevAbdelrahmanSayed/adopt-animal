<?php

namespace Modules\User\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\User\app\Http\Requests\ProfileRequest;
use Modules\User\app\Http\Requests\UpdatePasswordRequest;
use Modules\User\app\Resources\ProfileResource;
use Modules\User\Services\ProfileService;

class ProfileController extends Controller
{
    public function __construct(protected ProfileService $profileService){}

    public function index(): JsonResponse
    {
        try {
            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'User profile retrieved successfully', new ProfileResource(Auth::user()));
        } catch (\Throwable $e) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Error retrieving user profile.');
        }
    }
    public function update(ProfileRequest $request): JsonResponse
    {
        try {
            $responseData =  $this->profileService->update(Auth::user(),$request->validated());

            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'User profile updated successfully', new ProfileResource($responseData));
        } catch (\Exception $exception) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $exception->getMessage());
        }
    }

    public function changePassword(UpdatePasswordRequest $request): JsonResponse
    {
        try {

            if($this->profileService->changePassword(Auth::user(),$request->validated())){

                return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'password updated successfully',);
            }else{
                return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'old password not correct');
            }

        }catch (\Exception $exception){
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $exception->getMessage());
        }

    }
}
