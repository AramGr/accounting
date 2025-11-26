<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\BalanceHistory;
use App\Enum\BalanceOperationType;
use App\Repository\AccountRepositoryInterface;
use App\Repository\BalanceHistoryRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;

class BalanceService
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
        private readonly BalanceHistoryRepositoryInterface $historyRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Update account balance with transaction support
     *
     * @param string $amount Positive amount to add or remove
     * @param BalanceOperationType $type Operation type (add or remove)
     * @throws \DomainException if balance would be negative
     * @throws \RuntimeException if account not found
     */
    public function updateBalance(string $amount, BalanceOperationType $type): array
    {
        $this->entityManager->beginTransaction();

        try {
            $account = $this->accountRepository->findDefaultAccount();

            if (!$account) {
                throw new \RuntimeException('Account not found');
            }

            $balanceBefore = $account->getBalance();

            $changeAmount = $type === BalanceOperationType::REMOVE ? '-' . $amount : $amount;

            $account->applyBalanceChange($changeAmount);

            $balanceAfter = $account->getBalance();

            $history = new BalanceHistory(
                $account,
                $changeAmount,
                $balanceBefore,
                $balanceAfter
            );

            $this->entityManager->persist($account);
            $this->entityManager->persist($history);
            $this->entityManager->flush(); // This will throw OptimisticLockException if version mismatch
            $this->entityManager->commit();

            return [
                'balance' => $account->getBalanceAsFloat(),
                'type' => $type->value,
                'amount' => (float) $amount,
                'previous_balance' => (float) $balanceBefore,
                'version' => $account->getVersion(),
            ];
        } catch (OptimisticLockException $e) {
            $this->entityManager->rollback();
            throw new \RuntimeException(
                'Concurrent update detected. The balance was modified by another request. Please retry.',
                409,
                $e
            );
        } catch (\Throwable $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    /**
     * Get current balance
     *
     * @throws \RuntimeException if account not found
     */
    public function getBalance(): array
    {
        $account = $this->accountRepository->findDefaultAccount();

        if (!$account) {
            throw new \RuntimeException('Account not found');
        }

        return [
            'balance' => $account->getBalanceAsFloat(),
        ];
    }
}
