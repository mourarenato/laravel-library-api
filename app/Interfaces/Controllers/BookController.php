<?php

namespace App\Interfaces\Controllers;

use App\Application\Dtos\PaginationDto;
use App\Domain\Services\BookService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BookController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception|Throwable
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'title' => ['required', 'string', 'min:1', 'max:500'],
            'publication_year' => ['required', 'integer', 'digits:4', 'min:1000', 'max:9999'],
            'author_id' => ['required', 'integer'],
        ]);

        $service = app(BookService::class);

        $service->createBook();

        return response()->json([
            'success' => true,
            'message' => 'Book created successfully',
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception|Throwable
     */
    public function get(Request $request): JsonResponse
    {
        // GET /books?perPage=20&orderBy=email&orderDirection=desc&filters[name]=John

        $this->validate($request, [
            'perPage' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'orderBy' => ['sometimes', 'nullable', 'string', 'in:author_id,publication_year'],
            'orderDirection' => ['sometimes', 'nullable', 'string', 'in:asc,desc'],
            'filters' => ['sometimes', 'array'],
            'filters.author_id' => ['nullable', 'integer', 'min:1'],
            'filters.publication_year' => ['integer', 'digits:4', 'min:1000', 'max:9999']
        ]);

        $service = app(BookService::class);

        $paginationDto = new PaginationDto();
        $books = $service->getBooks($paginationDto);

        return response()->json([
            'data' => $books->toArray(),
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception|Throwable
     */
    public function update(Request $request): JsonResponse
    {
        $this->validate($request, [
            'id' => ['required', 'integer'],
            'title' => ['sometimes', 'string', 'min:1', 'max:500'],
            'publication_year' => ['required', 'integer', 'digits:4', 'min:1000', 'max:9999']
        ]);

        $service = app(BookService::class);

        $service->updateBook();

        return response()->json([
            'success' => true,
            'message' => 'Book updated with success'
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(Request $request): JsonResponse
    {
        $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $service = app(BookService::class);

        $service->deleteBook();

        return response()->json([
            'success' => true,
            'message' => 'Book deleted with success'
        ], Response::HTTP_OK);
    }

}
