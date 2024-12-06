<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class CoverLetterNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Cover Letter not found', Response::HTTP_NOT_FOUND);
    }
}