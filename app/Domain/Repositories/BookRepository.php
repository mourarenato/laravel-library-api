<?php

namespace App\Domain\Repositories;

use App\Application\Dtos\PaginationDto;
use App\Domain\Entities\Models\Book;
use Illuminate\Pagination\LengthAwarePaginator;

class BookRepository implements BaseRepositoryInterface
{
    public function getById(int $id): ?Book
    {
        return Book::where('id', $id)->first();
    }

    public function deleteById(int $id): void
    {
        Book::where('id', $id)->delete();
    }

    public function firstOrCreate(array $data): Book
    {
        return Book::firstOrCreate($data);
    }

    public function updateById(int $id, array $data): void
    {
        if (empty($data)) {
            return;
        }

        $book = Book::findOrFail($id);
        $book->fill($data);
        $book->save();
    }

    public function findOrFail(int $id): Book
    {
        return Book::findOrFail($id);
    }

    public function list(PaginationDto $paginationDto): LengthAwarePaginator
    {
        $query = Book::query();

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
}
