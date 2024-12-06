<?php

namespace App\Infrastructure\Mail;

use App\Application\Dtos\EmailDto;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public EmailDto $emailDto,
    )
    {
    }

    public function build(): SendEmail
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject($this->emailDto->subject)
            ->view('email');
    }
}
