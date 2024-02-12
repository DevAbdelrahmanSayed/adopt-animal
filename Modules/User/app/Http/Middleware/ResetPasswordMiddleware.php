<?php

namespace Modules\User\app\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResetPasswordMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->is_otp_verified) {
                return $next($request);
            }
        }

        return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'OTP verification required');
    }
}
