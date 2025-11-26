<?php

namespace App\Enum;

enum BalanceOperationType: string
{
    case ADD = 'add';
    case REMOVE = 'remove';
}
