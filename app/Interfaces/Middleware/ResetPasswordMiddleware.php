<?php

namespace App\Interfaces\Middleware;

use Closure;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ResetPasswordMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        try {
            $token = JWTAuth::parseToken()->authenticate();
            $isInvalidToken = $token->hasClaim('reset_password') && $token->getClaim('reset_password') === false;
            throw_if($isInvalidToken, new TokenInvalidException());

            return $next($request);
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json(['status' => 'Token is invalid'], 401);
            }

            if ($e instanceof TokenExpiredException) {
                return response()->json(['status' => 'Token is expired'], 401);
            }

            return response()->json(['status' => 'Authorization token not found'], 401);
        }
    }
}
