<?php

namespace App\Repository;

use App\Entity\Account;

interface AccountRepositoryInterface
{
    public function find(int $id): ?Account;

    public function save(Account $account): void;

    public function findDefaultAccount(): ?Account;
}
