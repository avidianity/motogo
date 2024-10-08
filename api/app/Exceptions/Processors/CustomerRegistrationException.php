<?php

namespace App\Exceptions\Processors;

use App\Exceptions\Exception;
use App\Traits\RendersException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CustomerRegistrationException extends Exception
{
    use RendersException;

    const KEY = 'CUSTOMER_REGISTRATION_ERROR';
    const STATUS_CODE = Response::HTTP_BAD_REQUEST;

    public function __construct(string $message = '', ?Throwable $previous = null)
    {
        parent::__construct($message, static::CUSTOMER_REGISTRATION_ERROR, $previous);
    }
}
