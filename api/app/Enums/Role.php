<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

enum Role: string
{
    use InvokableCases;
    use Values;

    case ADMIN = 'admin';
}
