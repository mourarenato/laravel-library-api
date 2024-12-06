<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AuthorNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Author not found', Response::HTTP_NOT_FOUND);
    }
}
