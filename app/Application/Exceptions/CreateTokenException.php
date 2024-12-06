<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class CreateTokenException extends Exception
{
    public function __construct()
    {
        parent::__construct('Could not create token', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
