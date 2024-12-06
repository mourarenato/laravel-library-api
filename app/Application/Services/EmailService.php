<?php

namespace App\Application\Services;

use App\Application\Dtos\EmailDto;
use App\Infrastructure\Jobs\SendEmailJob;
use Illuminate\Contracts\Bus\Dispatcher;
use Throwable;

class EmailService
{
    public function __construct(
        private readonly Dispatcher $dispatcher,
    )
    {
    }

    /**
     * @throws Throwable
     */
    public function sendEmail(EmailDto $emailDto): void
    {
        $this->dispatcher->dispatch(new SendEmailJob($emailDto));
    }
}
