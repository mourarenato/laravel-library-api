<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class CreateAuthorException extends Exception
{
    public function __construct()
    {
        parent::__construct('Could not create author', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
