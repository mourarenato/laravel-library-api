<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidCredentialsException extends Exception
{
    public function __construct($message = 'Credentials are invalid')
    {
        parent::__construct($message, Response::HTTP_BAD_REQUEST);
    }
}
