<?php

namespace App\Tests\Unit\Domain\Services;

use App\Application\Dtos\PaginationDto;
use App\Application\Exceptions\CreateLoanException;
use App\Application\Services\EmailService;
use App\Domain\Entities\Models\Loan;
use App\Domain\Repositories\LoanRepository;
use App\Domain\Services\LoanService;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use App\Tests\TestCase;
use Throwable;

class LoanServiceTest extends TestCase
{
    private LoanRepository $loanRepository;
    private EmailService $emailService;

    /**
     */
    public function __construct()
    {
        parent::__construct(...func_get_args());
        $this->loanRepository = $this->createMock(LoanRepository::class);
        $this->emailService = $this->createMock(EmailService::class);
    }

    public function testCreateLoanShouldCreateOneLoan(): void
    {
        $requestData = [
            "book_id" => 1,
            "loan_date" => "2024-12-01",
            "return_date" => "2025-01-01"
        ];

        $loanService = new LoanService($requestData, $this->loanRepository, $this->emailService);

        $this->loanRepository
            ->expects($this->once())
            ->method('getUserId')
            ->willReturn(1);

        $loanMock = Mockery::mock(Loan::class)
            ->shouldReceive('getAttribute')
            ->with('id')->andReturn(1)
            ->with('user_id')->andReturn(1)
            ->with('book_id')->andReturn(2)
            ->with('loan_date')->andReturn("2024-12-01")
            ->with('return_date')->andReturn("2025-01-01")
            ->shouldReceive('toArray')
            ->andReturn([
                'id' => 1,
                'user_id' => 1,
                'book_id' => 2,
                'loan_date' => "2024-12-01",
                'return_date' => "2025-01-01",
            ])
            ->getMock();

        $this->loanRepository
            ->expects($this->once())
            ->method('firstOrCreate')
            ->willReturn($loanMock);

        $this->emailService
            ->expects($this->once())
            ->method('sendEmail');

        $this->assertNull($loanService->createLoan());
    }

    public function testCreateLoanWhenExceptionIsThrown(): void
    {
        $this->expectException(CreateLoanException::class);
        $this->expectExceptionMessage('Could not create loan');

        $requestData = [
            "book_id" => 1,
            "loan_date" => "2024-12-01",
            "return_date" => "2025-01-01"
        ];

        $loanService = new LoanService($requestData, $this->loanRepository, $this->emailService);

        $this->loanRepository
            ->expects($this->once())
            ->method('getUserId')
            ->willThrowException(new Exception("Erro"));

        $this->assertNull($loanService->createLoan());
    }

    /**
     * @throws Throwable
     */
    public function testGetLoansShouldReturnLoans(): void
    {
        $authorService = new LoanService([], $this->loanRepository, $this->emailService);

        $paginatorMock = $this->createMock(LengthAwarePaginator::class);

        $this->loanRepository
            ->expects($this->once())
            ->method('list')
            ->willReturn($paginatorMock);

        $paginationDtoMock = new PaginationDto();

        $this->assertInstanceOf(LengthAwarePaginator::class, $authorService->getLoans($paginationDtoMock));
    }

    /**
     * @throws Throwable
     */
    public function testGetLoansWithFiltersShouldReturnFilteredLoans(): void
    {
        $requestData = [
            "filter" => [
                "book_id" => 1,
            ]
        ];

        $authorService = new LoanService($requestData, $this->loanRepository, $this->emailService);

        $paginatorMock = $this->createMock(LengthAwarePaginator::class);

        $this->loanRepository
            ->expects($this->once())
            ->method('list')
            ->willReturn($paginatorMock);

        $paginationDtoMock = new PaginationDto();

        $this->assertInstanceOf(LengthAwarePaginator::class, $authorService->getLoans($paginationDtoMock));
    }
}
