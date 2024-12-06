<?php

namespace App\Application\Dtos;

class EmailDto extends BaseDto
{
    public string $receiver;
    public string $subject;
    public array $body;
}
