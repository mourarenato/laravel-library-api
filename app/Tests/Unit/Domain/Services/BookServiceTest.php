<?php

namespace App\Tests\Unit\Domain\Services;

use App\Application\Dtos\PaginationDto;
use App\Application\Exceptions\BookNotFoundException;
use App\Application\Exceptions\CreateBookException;
use App\Application\Exceptions\DeleteBookException;
use App\Application\Exceptions\UpdateBookException;
use App\Domain\Entities\Models\Book;
use App\Domain\Repositories\BookRepository;
use App\Domain\Services\BookService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use App\Tests\TestCase;

class BookServiceTest extends TestCase
{
    private BookRepository $bookRepository;

    /**
     */
    public function __construct()
    {
        parent::__construct(...func_get_args());
        $this->bookRepository = $this->createMock(BookRepository::class);
    }

    public function testCreateBookShouldCreateOneBook(): void
    {
        $requestData = [
            "title" => "Desencantos",
            "publication_year" => 1861,
            "author_id" => 3
        ];

        $bookService = new BookService($requestData, $this->bookRepository);

        $this->bookRepository
            ->expects($this->once())
            ->method('firstOrCreate');

        $this->assertNull($bookService->createBook());
    }

    public function testCreateBookWhenExceptionIsThrown(): void
    {
        $this->expectException(CreateBookException::class);
        $this->expectExceptionMessage('Could not create book');

        $requestData = [
            "title" => "Desencantos",
            "publication_year" => 1861,
            "author_id" => 3
        ];

        $bookService = new BookService($requestData, $this->bookRepository);

        $this->bookRepository
            ->expects($this->once())
            ->method('firstOrCreate')
            ->willThrowException(new Exception("Erro"));

        $this->assertNull($bookService->createBook());
    }

    public function testUpdateBookShouldUpdateOneBook(): void
    {
        $requestData = [
            "id" => 1,
            "publication_year" => 1862,
        ];

        $bookService = new BookService($requestData, $this->bookRepository);

        $bookMock = Mockery::mock(Book::class)
            ->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(1)
            ->shouldReceive('getAttribute')
            ->with('publication_year')
            ->andReturn(1861)
            ->getMock();

        $this->bookRepository
            ->expects($this->once())
            ->method('findOrFail')
            ->willReturn($bookMock);

        $this->bookRepository
            ->expects($this->once())
            ->method('updateById');

        $this->assertNull($bookService->updateBook());
    }

    public function testUpdateBookWhenBookIsNotFound(): void
    {
        $this->expectException(BookNotFoundException::class);
        $this->expectExceptionMessage('Book not found');

        $requestData = [
            "id" => 1,
            "publication_year" => 1862,
        ];

        $bookService = new BookService($requestData, $this->bookRepository);

        $this->bookRepository
            ->expects($this->once())
            ->method('findOrFail')
            ->willThrowException(new ModelNotFoundException());

        $this->bookRepository
            ->expects($this->never())
            ->method('updateById');

        $this->assertNull($bookService->updateBook());
    }

    public function testUpdateBookWhenExceptionIsThrown(): void
    {
        $this->expectException(UpdateBookException::class);
        $this->expectExceptionMessage('Could not update book');

        $requestData = [
            "id" => 1,
            "publication_year" => 1862,
        ];

        $bookService = new BookService($requestData, $this->bookRepository);

        $bookMock = Mockery::mock(Book::class)
            ->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(1)
            ->shouldReceive('getAttribute')
            ->with('publication_year')
            ->andReturn(1862)
            ->getMock();

        $this->bookRepository
            ->expects($this->once())
            ->method('findOrFail')
            ->willReturn($bookMock);

        $this->bookRepository
            ->expects($this->once())
            ->method('updateById')
            ->willThrowException(new Exception("Erro"));;

        $this->assertNull($bookService->updateBook());
    }

    public function testDeleteBookShouldDeleteOneBook(): void
    {
        $requestData = [
            "id" => 1,
        ];

        $bookService = new BookService($requestData, $this->bookRepository);

        $bookMock = Mockery::mock(Book::class)
            ->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(1)
            ->shouldReceive('getAttribute')
            ->with('title')
            ->andReturn('Desencantos')
            ->getMock();

        $this->bookRepository
            ->expects($this->once())
            ->method('findOrFail')
            ->willReturn($bookMock);

        $this->bookRepository
            ->expects($this->once())
            ->method('deleteById');

        $this->assertNull($bookService->deleteBook());
    }

    public function testDeleteBookWhenBookIsNotFound(): void
    {
        $this->expectException(BookNotFoundException::class);
        $this->expectExceptionMessage('Book not found');

        $requestData = [
            "id" => 1,
        ];

        $bookService = new BookService($requestData, $this->bookRepository);

        $this->bookRepository
            ->expects($this->once())
            ->method('findOrFail')
            ->willThrowException(new ModelNotFoundException("Erro"));

        $this->bookRepository
            ->expects($this->never())
            ->method('deleteById');

        $this->assertNull($bookService->deleteBook());
    }

    public function testDeleteBookWhenExceptionIsThrown(): void
    {
        $this->expectException(DeleteBookException::class);
        $this->expectExceptionMessage('Could not delete book');

        $requestData = [
            "id" => 1,
        ];

        $bookService = new BookService($requestData, $this->bookRepository);

        $bookMock = Mockery::mock(Book::class)
            ->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(1)
            ->shouldReceive('getAttribute')
            ->with('title')
            ->andReturn('Desencantos')
            ->getMock();

        $this->bookRepository
            ->expects($this->once())
            ->method('findOrFail')
            ->willReturn($bookMock);

        $this->bookRepository
            ->expects($this->once())
            ->method('deleteById')
            ->willThrowException(new Exception("Erro"));;

        $this->assertNull($bookService->deleteBook());
    }

    /**
     * @throws \Throwable
     */
    public function testGetBooksShouldReturnBooks(): void
    {
        $bookService = new BookService([], $this->bookRepository);

        $paginatorMock = $this->createMock(LengthAwarePaginator::class);

        $this->bookRepository
            ->expects($this->once())
            ->method('list')
            ->willReturn($paginatorMock);

        $paginationDtoMock = new PaginationDto();

        $this->assertInstanceOf(LengthAwarePaginator::class, $bookService->getBooks($paginationDtoMock));
    }

    public function testGetBooksWithFiltersShouldReturnFilteredBooks(): void
    {
        $requestData = [
            "filter" => [
                "title" => "Desencantos",
            ]
        ];

        $bookService = new BookService($requestData, $this->bookRepository);

        $paginatorMock = $this->createMock(LengthAwarePaginator::class);

        $this->bookRepository
            ->expects($this->once())
            ->method('list')
            ->willReturn($paginatorMock);

        $paginationDtoMock = new PaginationDto();

        $this->assertInstanceOf(LengthAwarePaginator::class, $bookService->getBooks($paginationDtoMock));
    }
}
