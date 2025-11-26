<?php

namespace App\Repository;

use App\Entity\BalanceHistory;

interface BalanceHistoryRepositoryInterface
{
    public function save(BalanceHistory $history): void;
}
