<?php

namespace App\Exceptions\Auth;

use App\Exceptions\Exception;
use App\Traits\RendersException;
use Symfony\Component\HttpFoundation\Response;

class UserMissingException extends Exception
{
    use RendersException;

    const KEY = 'ERR_USER_MISSING';
    const STATUS_CODE = Response::HTTP_BAD_REQUEST;

    public function __construct(protected array $data)
    {
        parent::__construct('User does not exist', static::USER_MISSING);
    }

    public function context(): array
    {
        return $this->data;
    }
}
