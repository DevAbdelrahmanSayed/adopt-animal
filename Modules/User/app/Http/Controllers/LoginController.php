<?php

namespace Modules\User\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\User\app\Http\Requests\LoginRequest;
use Modules\User\app\Resources\LoginResource;
use Modules\User\Dto\UserDto;
use Modules\User\Services\LoginService;

class LoginController extends Controller
{
    public function store(LoginRequest $request, LoginService $loginService)
    {
        try {
            $requestData = UserDto::fromLoginRequest($request);
            $responseData = $loginService->store($requestData);

            if ($responseData === null) {
                return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'The email or password you entered is incorrect.');
            }

            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'User logged in successfully', new LoginResource($responseData));
        } catch (\Exception $exception) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $exception->getMessage());
        }
    }
}
