<?php

namespace App\Exceptions;

use Exception as BaseException;

abstract class Exception extends BaseException
{
    const USER_MISSING = 1000;
    const INCORRECT_PASSWORD = 1001;
}
