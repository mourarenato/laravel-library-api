<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class CreateBookException extends Exception
{
    public function __construct()
    {
        parent::__construct('Could not create book', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
