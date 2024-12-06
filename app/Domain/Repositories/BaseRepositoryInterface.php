<?php

namespace App\Domain\Repositories;

use App\Application\Dtos\PaginationDto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    public function getById(int $id): ?Model;

    public function deleteById(int $id): void;

    public function firstOrCreate(array $data): Model;

    public function list(PaginationDto $paginationDto): LengthAwarePaginator;
}
