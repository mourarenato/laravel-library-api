<?php

namespace App\Domain\Repositories;

use App\Application\Dtos\PaginationDto;
use App\Domain\Entities\Models\Loan;
use Illuminate\Pagination\LengthAwarePaginator;

class LoanRepository implements BaseRepositoryInterface
{
    public function getById(int $id): ?Loan
    {
        return Loan::where('id', $id)->first();
    }

    public function deleteById(int $id): void
    {
        Loan::where('id', $id)->delete();
    }

    public function firstOrCreate(array $data): Loan
    {
        return Loan::firstOrCreate($data);
    }

    public function list(PaginationDto $paginationDto): LengthAwarePaginator
    {
        $query = Loan::query();

        if (!empty($paginationDto->filters)) {
            foreach ($paginationDto->filters as $key => $value) {
                $query->where($key, 'like', "%$value%");
            }
        }

        if ($paginationDto->orderBy) {
            $query->orderBy($paginationDto->orderBy, $paginationDto->orderDirection);
        }

        return $query->paginate($paginationDto->perPage);
    }

    public function getUserId(): int
    {
        return auth()->id();
    }
}
