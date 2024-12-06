<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class BookNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Book not found', Response::HTTP_NOT_FOUND);
    }
}
