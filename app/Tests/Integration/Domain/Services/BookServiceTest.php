<?php

namespace App\Tests\Integration\Domain\Services;

use App\Application\Dtos\PaginationDto;
use App\Application\Exceptions\BookNotFoundException;
use App\Application\Exceptions\CreateBookException;
use App\Application\Exceptions\DeleteBookException;
use App\Domain\Entities\Models\Book;
use App\Domain\Repositories\BookRepository;
use App\Domain\Services\BookService;
use Database\Factories\AuthorFactory;
use Database\Factories\BookFactory;
use DateTime;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use App\Tests\TestCase;
use Throwable;

class BookServiceTest extends TestCase
{
    use RefreshDatabase;

    private BookRepository $bookRepository;

    /**
     */
    public function __construct()
    {
        parent::__construct(...func_get_args());
        $this->bookRepository = new BookRepository();
    }

    /**
     * @throws CreateBookException
     */
    public function testCreateBookShouldCreateOneBook(): void
    {
        $requestData = [
            "title" => "Desencantos",
            "publication_year" => 1861,
            "author_id" => 1
        ];

        AuthorFactory::new()->create([
            "id" => 1,
            'name' => "Machado de Assis",
            'birthdate' => "1839-06-21",
        ]);

        $bookService = new BookService($requestData, $this->bookRepository);
        $bookService->createBook();

        $book = Book::latest()->first();

        $this->assertEquals(1, $book->author_id);
        $this->assertEquals("Desencantos", $book->title);
    }

    /**
     * @throws Throwable
     */
    public function testUpdateBookShouldUpdateOneBook(): void
    {
        $requestData = [
            "id" => 1,
            "publication_year" => 1862,
        ];

        AuthorFactory::new()->create([
            "id" => 1,
            'name' => "Machado de Assis",
            'birthdate' => "1839-06-21",
        ]);

        BookFactory::new()->create([
            "id" => 1,
            "title" => "Desencantos",
            "publication_year" => 1861,
            "author_id" => 1
        ]);

        $bookService = new BookService($requestData, $this->bookRepository);
        $bookService->updateBook();

        $updatedBook = $this->bookRepository->getById(1);

        $this->assertEquals(1, $updatedBook->id);
        $this->assertEquals(1862, $updatedBook->publication_year);
    }

    /**
     * @throws BookNotFoundException
     * @throws DeleteBookException
     */
    public function testDeleteBookShouldDeleteOneBook(): void
    {
        $requestData = [
            "id" => 1,
        ];

        AuthorFactory::new()->create([
            "id" => 1,
            'name' => "Machado de Assis",
            'birthdate' => "1839-06-21",
        ]);

        $bookFactory = BookFactory::new()->create([
            "id" => 1,
            "title" => "Desencantos",
            "publication_year" => 1861,
            "author_id" => 1
        ]);

        $bookService = new BookService($requestData, $this->bookRepository);
        $bookService->deleteBook();

        $book = $this->bookRepository->getById($bookFactory->id);

        $this->assertNull($book);
    }

    /**
     * @throws Throwable
     */
    public function testGetBooksShouldReturnBooks(): void
    {
        AuthorFactory::new()->create([
            "id" => 1,
            'name' => "Machado de Assis",
            'birthdate' => "1839-06-21",
        ]);

        BookFactory::new()->create([
            "id" => 1,
            "title" => "Desencantos",
            "publication_year" => 1861,
            "author_id" => 1
        ]);

        BookFactory::new()->create([
            "id" => 2,
            "title" => "A cartomante",
            "publication_year" => 1884,
            "author_id" => 1
        ]);

        $expected = [
            [
                "id" => 1,
                "title" => "Desencantos",
                "publication_year" => 1861,
                "author_id" => 1
            ],
            [
                "id" => 2,
                "title" => "A cartomante",
                "publication_year" => 1884,
                "author_id" => 1
            ]
        ];

        $authorService = new BookService([], $this->bookRepository);

        $paginationDtoMock = new PaginationDto();

        $this->assertInstanceOf(LengthAwarePaginator::class, $authorService->getBooks($paginationDtoMock));
        $result = $authorService->getBooks($paginationDtoMock)->toArray()['data'];

        $result = array_map(function ($item) {
            unset($item['created_at']);
            unset($item['updated_at']);
            return $item;
        }, $result);

        $this->assertEquals($expected, $result);
    }

    /**
     * @throws Throwable
     */
    public function testGetBooksWithFiltersShouldReturnFilteredBooks(): void
    {
        AuthorFactory::new()->create([
            "id" => 1,
            'name' => "Machado de Assis",
            'birthdate' => "1839-06-21",
        ]);

        BookFactory::new()->create([
            "id" => 1,
            "title" => "Desencantos",
            "publication_year" => 1861,
            "author_id" => 1
        ]);

        BookFactory::new()->create([
            "id" => 2,
            "title" => "A cartomante",
            "publication_year" => 1884,
            "author_id" => 1
        ]);

        $requestData = [
            "filters" => [
                "publication_year" => 1884,
            ]
        ];

        $expected = [
            [
                "id" => 2,
                "title" => "A cartomante",
                "publication_year" => 1884,
                "author_id" => 1
            ]
        ];

        $bookService = new BookService($requestData, $this->bookRepository);

        $paginationDtoMock = new PaginationDto();

        $this->assertInstanceOf(LengthAwarePaginator::class, $bookService->getBooks($paginationDtoMock));
        $result = $bookService->getBooks($paginationDtoMock)->toArray()['data'];

        $result = array_map(function ($item) {
            unset($item['created_at']);
            unset($item['updated_at']);
            return $item;
        }, $result);

        $this->assertEquals($expected, $result);
    }
}
