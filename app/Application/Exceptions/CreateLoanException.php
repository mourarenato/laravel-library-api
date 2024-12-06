<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class CreateLoanException extends Exception
{
    public function __construct()
    {
        parent::__construct('Could not create loan', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
