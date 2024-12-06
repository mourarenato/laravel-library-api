<?php

namespace App\Tests\Integration\Domain\Services;

use App\Application\Exceptions\AuthorNotFoundException;
use App\Application\Exceptions\CreateAuthorException;
use App\Application\Exceptions\DeleteAuthorException;
use App\Domain\Entities\Models\Author;
use App\Domain\Repositories\AuthorRepository;
use App\Domain\Services\AuthorService;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Tests\TestCase;
use Throwable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Factories\AuthorFactory;

class AuthorServiceTest extends TestCase
{
    use RefreshDatabase;

    private AuthorRepository $authorRepository;

    /**
     */
    public function __construct()
    {
        parent::__construct(...func_get_args());
        $this->authorRepository = new AuthorRepository();
    }

    /**
     * @throws CreateAuthorException
     * @throws \DateMalformedStringException
     */
    public function testCreateAuthorShouldCreateOneAuthor(): void
    {
        $requestData = [
            "name" => "Guimarães Rosa",
            "birthdate" => "1908-06-27"
        ];

        $authorService = new AuthorService($requestData, $this->authorRepository);
        $authorService->createAuthor();

        $author = Author::latest()->first();

        $this->assertEquals("Guimarães Rosa", $author->name);
        $this->assertEquals(
            (new DateTime("1908-06-27"))->format('Y-m-d'),
            (new DateTime($author->birthdate))->format('Y-m-d')
        );
    }

    /**
     * @throws Throwable
     */
    public function testUpdateAuthorShouldUpdateOneAuthor(): void
    {
        $requestData = [
            "id" => 1,
            "name" => "Machado de Assis",
        ];

        $authorFactory = AuthorFactory::new()->create([
            "id" => 1,
            'name' => "Joaquim Maria Machado de Assis",
            'birthdate' => "1839-06-21",
        ]);

        $authorService = new AuthorService($requestData, $this->authorRepository);
        $authorService->updateAuthor();

        $updatedAuthor = $this->authorRepository->getById($authorFactory->id);

        $this->assertEquals("Machado de Assis", $updatedAuthor->name);
    }

    /**
     * @throws DeleteAuthorException
     * @throws AuthorNotFoundException
     */
    public function testDeleteAuthorShouldDeleteOneAuthor(): void
    {
        $requestData = [
            "id" => 1,
        ];

        $authorFactory = AuthorFactory::new()->create([
            "id" => 1,
            'name' => "Machado de Assis",
            'birthdate' => "1839-06-21",
        ]);

        $authorService = new AuthorService($requestData, $this->authorRepository);
        $authorService->deleteAuthor();

        $author = $this->authorRepository->getById($authorFactory->id);

        $this->assertNull($author);
    }

    /**
     * @throws Throwable
     */
    public function testGetAuthorsShouldReturnAuthors(): void
    {
        AuthorFactory::new()->create([
            "id" => 1,
            'name' => "Machado de Assis",
            'birthdate' => "1839-06-21",
        ]);

        AuthorFactory::new()->create([
            "id" => 2,
            'name' => "Clarice Lispector",
            'birthdate' => "1920-10-12",
        ]);

        $expected = [
            [
                "id" => 1,
                "name" => "Machado de Assis",
                "birthdate" => "1839-06-21 00:00:00",
            ],
            [
                "id" => 2,
                "name" => "Clarice Lispector",
                "birthdate" => "1920-10-12 00:00:00",
            ]
        ];

        $authorService = new AuthorService([], $this->authorRepository);

        $this->assertInstanceOf(LengthAwarePaginator::class, $authorService->getAuthors());
        $result = $authorService->getAuthors()->toArray()['data'];

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
    public function testGetAuthorsWithFiltersShouldReturnFilteredAuthors(): void
    {
        AuthorFactory::new()->create([
            "id" => 1,
            'name' => "Machado de Assis",
            'birthdate' => "1839-06-21",
        ]);

        AuthorFactory::new()->create([
            "id" => 2,
            'name' => "Clarice Lispector",
            'birthdate' => "1920-10-12",
        ]);

        $requestData = [
            "filters" => [
                "name" => "Machado",
            ]
        ];

        $expected = [
            [
                "id" => 1,
                "name" => "Machado de Assis",
                "birthdate" => "1839-06-21 00:00:00",
            ],
        ];

        $authorService = new AuthorService($requestData, $this->authorRepository);

        $this->assertInstanceOf(LengthAwarePaginator::class, $authorService->getAuthors());
        $result = $authorService->getAuthors()->toArray()['data'];

        $result = array_map(function ($item) {
            unset($item['created_at']);
            unset($item['updated_at']);
            return $item;
        }, $result);

        $this->assertEquals($expected, $result);
    }
}
