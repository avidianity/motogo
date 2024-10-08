<?php

namespace App\Exceptions;

use Exception as BaseException;

abstract class Exception extends BaseException
{
    const USER_MISSING = 1000;
    const PASSWORD_INVALID = 1001;
    const UNABLE_TO_DELETE_USER = 1002;
    const UNAUTHORIZED_ACTION = 1003;
    const RIDER_REGISTRATION_ERROR = 1004;
    const CUSTOMER_REGISTRATION_ERROR = 1005;
}
