<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UserAlreadyExistsException extends Exception
{
    public function __construct(string $email)
    {
        parent::__construct('User with email ' . $email . ' already exists!', Response::HTTP_CONFLICT);
    }
}
