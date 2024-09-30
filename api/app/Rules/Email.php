<?php

namespace App\Rules;

use Stringable;

class Email implements Stringable
{
    public function __toString(): string
    {
        if (app()->runningUnitTests() || !app()->isProduction()) {
            return 'email';
        }

        return 'email:rfc,dns,filter,spoof,strict';
    }
}
