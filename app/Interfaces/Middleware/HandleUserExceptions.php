<?php

namespace App\Interfaces\Middleware;

use App\Application\Exceptions\CreateTokenException;
use App\Application\Exceptions\InvalidCredentialsException;
use App\Application\Exceptions\UserAlreadyExistsException;
use App\Application\Exceptions\UserNotFoundException;
use Closure;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class HandleUserExceptions
{
    public function handle(Request $request, Closure $next): JsonResponse
    {
        try {
            $response = $next($request);

            if (!empty($response->exception)) {
                throw $response->exception;
            }

            return $response;
        } catch (Exception $e) {
            if ($e instanceof UserAlreadyExistsException) {
                Log::error(
                    'User already exists',
                    ['user_id' => $this->getCurrentUserId(), 'error' => $e->getMessage()]
                );
                return response()->json([
                    'success' => false,
                    'message' => 'Error trying to create user',
                ], Response::HTTP_BAD_REQUEST);
            }

            if ($e instanceof UserNotFoundException) {
                Log::error(
                    'User not found',
                    ['error' => $e->getMessage()]
                );
                return response()->json([
                    'success' => false,
                    'message' => 'Error trying to find user',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ($e instanceof InvalidCredentialsException) {
                Log::error(
                    'Credentials are invalid',
                    ['error' => $e->getMessage()]
                );
                return response()->json([
                    'success' => false,
                    'message' => 'Credentials are invalid',
                ], Response::HTTP_BAD_REQUEST);
            }

            if ($e instanceof CreateTokenException) {
                Log::error(
                    'Could not create token',
                    ['error' => $e->getMessage()]
                );
                return response()->json([
                    'success' => false,
                    'message' => 'Error trying to create token',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            Log::error(
                'Error trying to process your request',
                ['user_id' => $this->getCurrentUserId(), 'error' => $e->getMessage()]
            );
            return response()->json([
                'success' => false,
                'message' => 'Error trying to process your request.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Throwable $e) {
            Log::error(
                'Error trying to process your request',
                ['user_id' => $this->getCurrentUserId(), 'error' => $e->getMessage()]
            );
            return response()->json([
                'success' => false,
                'message' => 'Error trying to process your request.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getCurrentUserId(): int
    {
        return auth()->id();
    }
}
