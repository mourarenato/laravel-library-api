<?php

namespace App\Domain\Services;

use App\Application\Dtos\PaginationDto;
use App\Application\Exceptions\AuthorNotFoundException;
use App\Application\Exceptions\CreateAuthorException;
use App\Application\Exceptions\DeleteAuthorException;
use App\Application\Exceptions\UpdateAuthorException;
use App\Domain\Repositories\AuthorRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

class AuthorService
{
    public function __construct(
        protected array                   $requestData,
        private readonly AuthorRepository $authorRepository,
    )
    {
    }

    /**
     * @throws CreateAuthorException
     */
    public function createAuthor(): void
    {
        try {
            $data = $this->requestData;
            $this->authorRepository->firstOrCreate($data);  //1920-12-10 YYYY-MM-DD
        } catch (Throwable $e) {
            throw new CreateAuthorException();
        }
    }

    /**
     * @throws Throwable
     */
    public function updateAuthor(): void
    {
        try {
            $author = $this->authorRepository->findOrFail($this->requestData['id']);
            $this->authorRepository->updateById($author->id, $this->requestData);
        } catch (ModelNotFoundException) {
            throw new AuthorNotFoundException();
        } catch (Throwable $e) {
            throw new UpdateAuthorException();
        }
    }

    /**
     * @throws DeleteAuthorException
     * @throws AuthorNotFoundException
     */
    public function deleteAuthor(): void
    {
        try {
            $author = $this->authorRepository->findOrFail($this->requestData['id']);
            $this->authorRepository->deleteById($author->id);
        } catch (ModelNotFoundException) {
            throw new AuthorNotFoundException();
        } catch (Throwable $e) {
            throw new DeleteAuthorException();
        }
    }

    /**
     * @throws Throwable
     */
    public function getAuthors(): LengthAwarePaginator
    {
        $perPage = $this->requestData['perPage'] ?? 10;
        $orderBy = $this->requestData['orderBy'] ?? null;
        $orderDirection = $this->requestData['orderDirection'] ?? 'asc';
        $filters = $this->requestData['filters'] ?? [];

        $paginationDto = new PaginationDto();
        $paginationDto->attachValues([
            'perPage' => $perPage,
            'orderBy' => $orderBy,
            'orderDirection' => $orderDirection,
            'filters' => $filters
        ]);

        return $this->authorRepository->list($paginationDto);
    }
}
