<?php

namespace App\Interfaces\Middleware;

use App\Application\Exceptions\AuthorNotFoundException;
use App\Application\Exceptions\BookNotFoundException;
use App\Application\Exceptions\CreateAuthorException;
use App\Application\Exceptions\CreateBookException;
use App\Application\Exceptions\CreateLoanException;
use App\Application\Exceptions\DeleteAuthorException;
use App\Application\Exceptions\DeleteBookException;
use App\Application\Exceptions\LoanNotFoundException;
use App\Application\Exceptions\UpdateAuthorException;
use App\Application\Exceptions\UpdateBookException;
use Closure;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class HandleLibraryExceptions
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
            if ($e instanceof AuthorNotFoundException) {
                Log::error(
                    'Author not found',
                    ['user_id' => $this->getCurrentUserId(), 'error' => $e->getMessage()]
                );
                return response()->json([
                    'success' => false,
                    'message' => 'Error trying to find author',
                    'errors' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ($e instanceof CreateAuthorException) {
                Log::error(
                    'Error trying to create author',
                    ['user_id' => $this->getCurrentUserId(), 'error' => $e->getMessage()]
                );
                return response()->json([
                    'success' => false,
                    'message' => 'Error trying to create author',
                    'errors' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ($e instanceof UpdateAuthorException) {
                Log::error(
                    'Error trying to update author',
                    ['user_id' => $this->getCurrentUserId(), 'error' => $e->getMessage()]
                );
                return response()->json([
                    'success' => false,
                    'message' => 'Error trying to update author',
                    'errors' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ($e instanceof DeleteAuthorException) {
                Log::error(
                    'Error trying to delete author',
                    ['user_id' => $this->getCurrentUserId(), 'error' => $e->getMessage()]
                );
                return response()->json([
                    'success' => false,
                    'message' => 'Error trying to delete author',
                    'errors' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ($e instanceof BookNotFoundException) {
                Log::error(
                    'Book not found',
                    ['user_id' => $this->getCurrentUserId(), 'error' => $e->getMessage()]
                );
                return response()->json([
                    'success' => false,
                    'message' => 'Error trying to find book',
                    'errors' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ($e instanceof CreateBookException) {
                Log::error(
                    'Error trying to create book',
                    ['user_id' => $this->getCurrentUserId(), 'error' => $e->getMessage()]
                );
                return response()->json([
                    'success' => false,
                    'message' => 'Error trying to create book',
                    'errors' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ($e instanceof UpdateBookException) {
                Log::error(
                    'Error trying to update book',
                    ['user_id' => $this->getCurrentUserId(), 'error' => $e->getMessage()]
                );
                return response()->json([
                    'success' => false,
                    'message' => 'Error trying to update book',
                    'errors' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ($e instanceof DeleteBookException) {
                Log::error(
                    'Error trying to delete book',
                    ['user_id' => $this->getCurrentUserId(), 'error' => $e->getMessage()]
                );
                return response()->json([
                    'success' => false,
                    'message' => 'Error trying to delete book',
                    'errors' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ($e instanceof LoanNotFoundException) {
                Log::error(
                    'Loan not found',
                    ['user_id' => $this->getCurrentUserId(), 'error' => $e->getMessage()]
                );
                return response()->json([
                    'success' => false,
                    'message' => 'Error trying to find loan',
                    'errors' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ($e instanceof CreateLoanException) {
                Log::error(
                    'Error trying to create loan',
                    ['user_id' => $this->getCurrentUserId(), 'error' => $e->getMessage()]
                );
                return response()->json([
                    'success' => false,
                    'message' => 'Error trying to create loan',
                    'errors' => $e->getMessage()
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
                'errors' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getCurrentUserId(): int
    {
        return auth()->id();
    }
}
