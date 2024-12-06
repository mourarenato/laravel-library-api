<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UserNotFoundException extends Exception
{
    public function __construct($message = 'User not found')
    {
        parent::__construct($message, Response::HTTP_NOT_FOUND);
    }
}
