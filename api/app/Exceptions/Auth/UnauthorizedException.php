<?php

namespace App\Exceptions\Auth;

use App\Exceptions\Exception;
use App\Traits\RendersException;
use Symfony\Component\HttpFoundation\Response;

class UnauthorizedException extends Exception
{
    use RendersException;

    const KEY = 'UNAUTHORIZED_ACTION';
    const STATUS_CODE = Response::HTTP_UNAUTHORIZED;

    public function __construct(string $message = 'Unauthorized action')
    {
        parent::__construct($message, static::UNAUTHORIZED_ACTION);
    }
}
