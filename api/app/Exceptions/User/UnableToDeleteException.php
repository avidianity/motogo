<?php

namespace App\Exceptions\User;

use App\Exceptions\Exception;
use App\Traits\RendersException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UnableToDeleteException extends Exception
{
    use RendersException;

    const KEY = 'UNABLE_TO_DELETE';
    const STATUS_CODE = Response::HTTP_BAD_REQUEST;

    public function __construct(string $message = '', ?Throwable $previous = null)
    {
        parent::__construct($message, static::UNABLE_TO_DELETE_USER, $previous);
    }
}
