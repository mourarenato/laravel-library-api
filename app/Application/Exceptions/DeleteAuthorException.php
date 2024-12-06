<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class DeleteAuthorException extends Exception
{
    public function __construct()
    {
        parent::__construct('Could not delete author', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
