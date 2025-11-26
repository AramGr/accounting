<?php

namespace App\Repository;

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Account>
 */
class AccountRepository extends ServiceEntityRepository implements AccountRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?Account
    {
        /** @var Account|null $account */
        $account = parent::find($id, $lockMode, $lockVersion);

        return $account;
    }

    public function save(Account $account): void
    {
        $this->getEntityManager()->persist($account);
        $this->getEntityManager()->flush();
    }

    public function findDefaultAccount(): ?Account
    {
        return $this->find(1);
    }
}
