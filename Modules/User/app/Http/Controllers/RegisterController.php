<?php

namespace Modules\User\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\User\app\Http\Requests\RegisterRequest;
use Modules\User\app\Resources\RegisterResource;
use Modules\User\Dto\UserDto;
use Modules\User\Services\RegisterService;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request, RegisterService $registerService)
    {
        $requestData = UserDto::fromRegisterRequest($request);
        $responseData = $registerService->store($requestData);

        return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'User Account Created Successfully', new RegisterResource($responseData));

    }
}
