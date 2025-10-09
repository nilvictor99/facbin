<?php

namespace App\Enums;

use App\Traits\Utils\HasEnums;

enum TypeMovementEnum: string
{
    use HasEnums;

    case INCOME = 'Income';
    case EXPENSE = 'Expense';
}
