<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class LoanNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Loan not found', Response::HTTP_NOT_FOUND);
    }
}
