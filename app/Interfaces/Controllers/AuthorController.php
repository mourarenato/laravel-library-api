<?php

namespace App\Interfaces\Controllers;

use App\Application\Dtos\PaginationDto;
use App\Domain\Services\AuthorService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthorController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception|Throwable
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'birthdate' => ['required', 'date'],  //YYYY-MM-DD, MM/DD/YYYY, DD/MM/YYYY
        ]);

        $service = app(AuthorService::class);

        $service->createAuthor();

        return response()->json([
            'success' => true,
            'message' => 'Author created successfully',
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception|Throwable
     */
    public function get(Request $request): JsonResponse
    {
        // GET /authors?perPage=20&orderBy=email&orderDirection=desc&filters[name]=John

        $this->validate($request, [
            'perPage' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'orderBy' => ['sometimes', 'nullable', 'string', 'in:name,email'],
            'orderDirection' => ['sometimes', 'nullable', 'string', 'in:asc,desc'],
            'filters' => ['sometimes', 'array'],
            'filters.name' => ['nullable', 'string', 'min:3', 'max:100'],
            'filters.birthdate' => ['nullable', 'date'],
        ]);

        $service = app(AuthorService::class);

        $paginationDto = new PaginationDto();
        $authors = $service->getAuthors($paginationDto);

        return response()->json([
            'data' => $authors->toArray(),
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
            'name' => ['sometimes', 'string', 'min:3', 'max:100'],
            'birthdate' => ['sometimes', 'date'],
        ]);

        $service = app(AuthorService::class);

        $service->updateAuthor();

        return response()->json([
            'success' => true,
            'message' => 'Author updated with success'
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

        $service = app(AuthorService::class);

        $service->deleteAuthor();

        return response()->json([
            'success' => true,
            'message' => 'Author deleted with success'
        ], Response::HTTP_OK);
    }
}
