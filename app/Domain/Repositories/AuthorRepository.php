<?php

namespace App\Domain\Repositories;

use App\Application\Dtos\PaginationDto;
use App\Domain\Entities\Models\Author;
use Illuminate\Pagination\LengthAwarePaginator;

class AuthorRepository implements BaseRepositoryInterface
{
    public function getById(int $id): ?Author
    {
        return Author::where('id', $id)->first();
    }

    public function deleteById(int $id): void
    {
        Author::where('id', $id)->delete();
    }

    public function firstOrCreate(array $data): Author
    {
        return Author::firstOrCreate($data);
    }

    public function updateById(int $id, array $data): void
    {
        if (empty($data)) {
            return;
        }

        $author = Author::findOrFail($id);
        $author->fill($data);
        $author->save();
    }

    public function findOrFail(int $id): Author
    {
        return Author::findOrFail($id);
    }

    public function list(PaginationDto $paginationDto): LengthAwarePaginator
    {
        $query = Author::query();

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
