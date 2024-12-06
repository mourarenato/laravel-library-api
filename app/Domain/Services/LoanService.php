<?php

namespace App\Domain\Services;

use App\Application\Dtos\EmailDto;
use App\Application\Dtos\PaginationDto;
use App\Application\Exceptions\CreateLoanException;
use App\Application\Services\EmailService;
use App\Domain\Repositories\LoanRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

class LoanService
{
    public function __construct(
        protected array                 $requestData,
        private readonly LoanRepository $loanRepository,
        private readonly EmailService   $emailService,
    )
    {
    }

    /**
     * @throws CreateLoanException
     */
    public function createLoan(): void
    {
        try {
            $data = $this->requestData;
            $data['user_id'] = $this->loanRepository->getUserId();
            $loan = $this->loanRepository->firstOrCreate($data);
            $emailDto = new EmailDto();
            $emailDto->attachValues([
                'receiver' => 'mytestmail@example.com',
                'subject' => 'New loan registered',
                'body' => $loan->toArray(),
            ]);
            $this->emailService->sendEmail($emailDto);
        } catch (Throwable $e) {
            throw new CreateLoanException();
        }
    }

    /**
     * @throws Throwable
     */
    public function getLoans(PaginationDto $paginationDto): LengthAwarePaginator
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

        return $this->loanRepository->list($paginationDto);
    }
}
