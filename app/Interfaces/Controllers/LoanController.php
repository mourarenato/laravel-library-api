<?php

namespace App\Interfaces\Controllers;

use App\Application\Dtos\PaginationDto;
use App\Domain\Services\LoanService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LoanController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception|Throwable
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'book_id' => ['required', 'integer'],
            'loan_date' => ['required', 'date'],
            'return_date' => ['required', 'date'],
        ]);

        $service = app(LoanService::class);

        $service->createLoan();

        return response()->json([
            'success' => true,
            'message' => 'Loan created successfully',
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
            'filters.birthdate' => ['nullable', 'date', 'email'],
        ]);

        $service = app(LoanService::class);

        $paginationDto = new PaginationDto();
        $loans = $service->getLoans($paginationDto);

        return response()->json([
            'data' => $loans->toArray(),
        ], Response::HTTP_OK);
    }
}
