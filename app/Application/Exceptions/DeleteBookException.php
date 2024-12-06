<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class DeleteBookException extends Exception
{
    public function __construct()
    {
        parent::__construct('Could not delete book', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
