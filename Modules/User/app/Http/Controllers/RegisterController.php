<?php

namespace Modules\User\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\User\app\Http\Requests\RegisterRequest;
use Modules\User\app\Models\User;
use Modules\User\app\Resources\RegisterResource;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class RegisterController extends Controller
{
    public function storeRegister(RegisterRequest $request)
    {
        $userData = User::create($request->validated());
        $userData['token'] = JWTAuth::fromUser($userData);
        return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'User Account Created Successfully',new RegisterResource($userData));

    }
}
