<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UpdateBookException extends Exception
{
    public function __construct()
    {
        parent::__construct('Could not update book', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
