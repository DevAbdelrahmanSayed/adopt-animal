<?php

namespace Modules\User\app\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\User\app\Http\Requests\LoginRequest;
use Modules\User\app\Resources\LoginResource;

class LoginController extends Controller
{
    public function storeLogin(LoginRequest $request)
    {
        $credentials = [
            'password' => $request->input('password'),
        ];
        $loginIdentifier = $request->input('username_email');
        $type = filter_var($loginIdentifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials[$type] = $loginIdentifier;
        $tokenIfCredentialsWorks = Auth::guard('user')->attempt($credentials);
        if ($tokenIfCredentialsWorks) {
            $currentUser = Auth::guard('user')->user();
            $currentUser['token'] = $tokenIfCredentialsWorks;

            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'user logged in Successfully', new LoginResource($currentUser));
        } else {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'User credentials do not work', []);

        }

    }
    public function errors()
    {
        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'erorrs');
    }
}
