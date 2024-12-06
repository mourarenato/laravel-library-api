<?php

namespace App\Tests\Unit\Domain\Services;

use App\Application\Exceptions\AuthorNotFoundException;
use App\Application\Exceptions\CreateAuthorException;
use App\Application\Exceptions\DeleteAuthorException;
use App\Application\Exceptions\UpdateAuthorException;
use App\Domain\Entities\Models\Author;
use App\Domain\Repositories\AuthorRepository;
use App\Domain\Services\AuthorService;
use App\Tests\TestCase;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;

class AuthorServiceTest extends TestCase
{
    private AuthorRepository $authorRepository;

    /**
     */
    public function __construct()
    {
        parent::__construct(...func_get_args());
        $this->authorRepository = $this->createMock(AuthorRepository::class);
    }

    public function testCreateAuthorShouldCreateOneAuthor(): void
    {
        $requestData = [
            "name" => "Guimarães Rosa",
            "birthdate" => "1908-06-27"
        ];

        $authorService = new AuthorService($requestData, $this->authorRepository);

        $this->authorRepository
            ->expects($this->once())
            ->method('firstOrCreate');

        $this->assertNull($authorService->createAuthor());
    }

    public function testCreateAuthorWhenExceptionIsThrown(): void
    {
        $this->expectException(CreateAuthorException::class);
        $this->expectExceptionMessage('Could not create author');

        $requestData = [
            "name" => "Guimarães Rosa",
            "birthdate" => "1908-06-27"
        ];

        $authorService = new AuthorService($requestData, $this->authorRepository);

        $this->authorRepository
            ->expects($this->once())
            ->method('firstOrCreate')
            ->willThrowException(new Exception("Erro"));

        $this->assertNull($authorService->createAuthor());
    }

    public function testUpdateAuthorShouldUpdateOneAuthor(): void
    {
        $requestData = [
            "id" => 3,
            "name" => "João Guimarães Rosa",
        ];

        $authorService = new AuthorService($requestData, $this->authorRepository);

        $authorMock = Mockery::mock(Author::class)
            ->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(3)
            ->shouldReceive('getAttribute')
            ->with('name')
            ->andReturn('Guimarães Rosa')
            ->getMock();

        $this->authorRepository
            ->expects($this->once())
            ->method('findOrFail')
            ->willReturn($authorMock);

        $this->authorRepository
            ->expects($this->once())
            ->method('updateById');

        $this->assertNull($authorService->updateAuthor());
    }

    public function testUpdateAuthorWhenAuthorIsNotFound(): void
    {
        $this->expectException(AuthorNotFoundException::class);
        $this->expectExceptionMessage('Author not found');

        $requestData = [
            "id" => 3,
            "name" => "João Guimarães Rosa",
        ];

        $authorService = new AuthorService($requestData, $this->authorRepository);

        $this->authorRepository
            ->expects($this->once())
            ->method('findOrFail')
            ->willThrowException(new ModelNotFoundException("Erro"));

        $this->authorRepository
            ->expects($this->never())
            ->method('updateById');

        $this->assertNull($authorService->updateAuthor());
    }

    public function testUpdateAuthorWhenExceptionIsThrown(): void
    {
        $this->expectException(UpdateAuthorException::class);
        $this->expectExceptionMessage('Could not update author');

        $requestData = [
            "id" => 3,
            "name" => "João Guimarães Rosa",
        ];

        $authorService = new AuthorService($requestData, $this->authorRepository);

        $authorMock = Mockery::mock(Author::class)
            ->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(3)
            ->shouldReceive('getAttribute')
            ->with('name')
            ->andReturn('Guimarães Rosa')
            ->getMock();

        $this->authorRepository
            ->expects($this->once())
            ->method('findOrFail')
            ->willReturn($authorMock);

        $this->authorRepository
            ->expects($this->once())
            ->method('updateById')
            ->willThrowException(new Exception("Erro"));;

        $this->assertNull($authorService->updateAuthor());
    }

    public function testDeleteAuthorShouldDeleteOneAuthor(): void
    {
        $requestData = [
            "id" => 3,
            "name" => "Guimarães Rosa",
        ];

        $authorService = new AuthorService($requestData, $this->authorRepository);

        $authorMock = Mockery::mock(Author::class)
            ->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(3)
            ->shouldReceive('getAttribute')
            ->with('name')
            ->andReturn('Guimarães Rosa')
            ->getMock();

        $this->authorRepository
            ->expects($this->once())
            ->method('findOrFail')
            ->willReturn($authorMock);

        $this->authorRepository
            ->expects($this->once())
            ->method('deleteById');

        $this->assertNull($authorService->deleteAuthor());
    }

    public function testDeleteAuthorWhenAuthorIsNotFound(): void
    {
        $this->expectException(AuthorNotFoundException::class);
        $this->expectExceptionMessage('Author not found');

        $requestData = [
            "id" => 3,
            "name" => "Guimarães Rosa",
        ];

        $authorService = new AuthorService($requestData, $this->authorRepository);

        $this->authorRepository
            ->expects($this->once())
            ->method('findOrFail')
            ->willThrowException(new ModelNotFoundException("Erro"));

        $this->authorRepository
            ->expects($this->never())
            ->method('deleteById');

        $this->assertNull($authorService->deleteAuthor());
    }

    public function testDeleteAuthorWhenExceptionIsThrown(): void
    {
        $this->expectException(DeleteAuthorException::class);
        $this->expectExceptionMessage('Could not delete author');

        $requestData = [
            "id" => 3,
            "name" => "Guimarães Rosa",
        ];

        $authorService = new AuthorService($requestData, $this->authorRepository);

        $authorMock = Mockery::mock(Author::class)
            ->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(3)
            ->shouldReceive('getAttribute')
            ->with('name')
            ->andReturn('Guimarães Rosa')
            ->getMock();

        $this->authorRepository
            ->expects($this->once())
            ->method('findOrFail')
            ->willReturn($authorMock);

        $this->authorRepository
            ->expects($this->once())
            ->method('deleteById')
            ->willThrowException(new Exception("Erro"));;

        $this->assertNull($authorService->deleteAuthor());
    }

    /**
     * @throws \Throwable
     */
    public function testGetAuthorsShouldReturnAuthors(): void
    {
        $authorService = new AuthorService([], $this->authorRepository);

        $paginatorMock = $this->createMock(LengthAwarePaginator::class);

        $this->authorRepository
            ->expects($this->once())
            ->method('list')
            ->willReturn($paginatorMock);

        $this->assertInstanceOf(LengthAwarePaginator::class, $authorService->getAuthors());
    }

    /**
     * @throws \Throwable
     */
    public function testGetAuthorsWithFiltersShouldReturnFilteredAuthors(): void
    {
        $requestData = [
            "filter" => [
                "name" => "Guimarães Rosa",
            ]
        ];

        $authorService = new AuthorService($requestData, $this->authorRepository);

        $paginatorMock = $this->createMock(LengthAwarePaginator::class);

        $this->authorRepository
            ->expects($this->once())
            ->method('list')
            ->willReturn($paginatorMock);

        $this->assertInstanceOf(LengthAwarePaginator::class, $authorService->getAuthors());
    }
}
