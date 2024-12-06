<?php

namespace App\Infrastructure\Jobs;

use App\Application\Dtos\EmailDto;
use App\Infrastructure\Mail\SendEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public EmailDto $emailDto,
    )
    {
    }

    public function handle(): void
    {
        $message = new SendEmail($this->emailDto);
        Mail::to($this->emailDto->receiver)->send($message);
    }
}
