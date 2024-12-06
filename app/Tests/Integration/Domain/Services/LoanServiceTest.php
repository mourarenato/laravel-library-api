<?php

namespace App\Tests\Integration\Domain\Services;

use App\Application\Services\EmailService;
use App\Domain\Entities\Models\Loan;
use App\Domain\Repositories\LoanRepository;
use App\Domain\Services\LoanService;
use Database\Factories\AuthorFactory;
use Database\Factories\BookFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class LoanServiceTest extends TestCase
{
    use RefreshDatabase;

    private LoanRepository $loanRepository;
    private EmailService $emailService;

    /**
     */
    public function __construct()
    {
        parent::__construct(...func_get_args());
        $this->loanRepository = new LoanRepository();
        $this->emailService = $this->createMock(EmailService::class);
    }

    public function testCreateLoanShouldCreateOneLoan(): void
    {
        $this->markTestSkipped('Fix in the future');

        $requestData = [
            "book_id" => 1,
            "loan_date" => "2024-12-01",
            "return_date" => "2025-01-01"
        ];

        AuthorFactory::new()->create([
            "id" => 1,
            "name" => "Machado de Assis",
            "birthdate" => "1839-06-21",
        ]);

        BookFactory::new()->create([
            "id" => 1,
            "title" => "Desencantos",
            "publication_year" => 1861,
            "author_id" => 1
        ]);

        $loanService = new LoanService($requestData, $this->loanRepository, $this->emailService);
        $loanService->createLoan();

        $loan = Loan::latest()->first();

        $this->assertEquals(1, $loan->book_id);
    }
}
