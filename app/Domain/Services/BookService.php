<?php

namespace App\Domain\Services;

use App\Application\Dtos\PaginationDto;
use App\Application\Exceptions\BookNotFoundException;
use App\Application\Exceptions\CreateBookException;
use App\Application\Exceptions\DeleteBookException;
use App\Application\Exceptions\UpdateBookException;
use App\Domain\Repositories\BookRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

class BookService
{
    public function __construct(
        protected array                 $requestData,
        private readonly BookRepository $bookRepository,
    )
    {
    }

    /**
     * @throws CreateBookException
     */
    public function createBook(): void
    {
        try {
            $this->bookRepository->firstOrCreate($this->requestData);
        } catch (Throwable $e) {
            throw new CreateBookException();
        }
    }

    /**
     * @throws bookNotFoundException
     * @throws DeleteBookException
     */
    public function deleteBook(): void
    {
        try {
            $book = $this->bookRepository->findOrFail($this->requestData['id']);
            $this->bookRepository->deleteById($book->id);
        } catch (ModelNotFoundException) {
            throw new BookNotFoundException();
        } catch (Throwable $e) {
            throw new DeleteBookException();
        }
    }


    /**
     * @throws Throwable
     */
    public function updateBook(): void
    {
        try {
            $book = $this->bookRepository->findOrFail($this->requestData['id']);
            $this->bookRepository->updateById($book->id, $this->requestData);
        } catch (ModelNotFoundException) {
            throw new BookNotFoundException();
        } catch (Throwable $e) {
            throw new UpdateBookException();
        }
    }

    /**
     * @throws Throwable
     */
    public function getBooks(PaginationDto $paginationDto): LengthAwarePaginator
    {
        $perPage = $this->requestData['perPage'] ?? 10;
        $orderBy = $this->requestData['orderBy'] ?? null;
        $orderDirection = $this->requestData['orderDirection'] ?? 'asc';
        $filters = $this->requestData['filters'] ?? [];

        $paginationDto->attachValues([
            'perPage' => $perPage,
            'orderBy' => $orderBy,
            'orderDirection' => $orderDirection,
            'filters' => $filters
        ]);

        return $this->bookRepository->list($paginationDto);
    }
}
