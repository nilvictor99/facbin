<?php

namespace App\Enums;

use App\Traits\Utils\HasEnums;

enum MovementTypeEnum: string
{
    use HasEnums;

    case INCOME = 'Income';
    case OUTPUT = 'Output';
    case TRANSFER = 'Transfer';
    case STABLE = 'Stable';
}
