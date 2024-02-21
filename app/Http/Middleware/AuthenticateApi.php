<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            return $next($request);
        } catch (\Exception $e) {
            // Token is invalid or not provided
            return ApiResponse::sendResponse('401', 'unauthenticated');

        }

    }
}
