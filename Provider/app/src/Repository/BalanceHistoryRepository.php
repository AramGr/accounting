<?php

namespace App\Repository;

use App\Entity\BalanceHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BalanceHistoryRepository extends ServiceEntityRepository implements BalanceHistoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BalanceHistory::class);
    }

    public function save(BalanceHistory $history): void
    {
        $this->getEntityManager()->persist($history);
        $this->getEntityManager()->flush();
    }
}
