<?php

namespace App\Entity;

use App\Repository\BalanceHistoryRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: BalanceHistoryRepository::class)]
#[ORM\Table(name: 'balance_history')]
class BalanceHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(name: 'account_id', nullable: false, onDelete: 'CASCADE')]
    private Account $account;

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2)]
    private string $changeAmount;

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2)]
    private string $balanceBefore;

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2)]
    private string $balanceAfter;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    public function __construct(
        Account $account,
        string $changeAmount,
        string $balanceBefore,
        string $balanceAfter
    ) {
        $this->account = $account;
        $this->changeAmount = $changeAmount;
        $this->balanceBefore = $balanceBefore;
        $this->balanceAfter = $balanceAfter;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function getChangeAmount(): string
    {
        return $this->changeAmount;
    }

    public function getBalanceBefore(): string
    {
        return $this->balanceBefore;
    }

    public function getBalanceAfter(): string
    {
        return $this->balanceAfter;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
