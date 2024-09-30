<?php

namespace App\Exceptions\Auth;

use App\Exceptions\Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidPasswordException extends Exception
{
    const KEY = 'INVALID_PASSWORD';
    const STATUS_CODE = Response::HTTP_BAD_REQUEST;

    public function __construct()
    {
        parent::__construct('Password is incorrect', static::INCORRECT_PASSWORD);
    }
}
