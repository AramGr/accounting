<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\Table(name: 'accounts')]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2)]
    private string $balance;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Version]
    #[ORM\Column(type: 'integer')]
    private int $version = 1;

    public function __construct()
    {
        $this->balance = '0.00';
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBalance(): string
    {
        return $this->balance;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * Apply a balance change.
     *
     * @throws \DomainException if resulting balance would be negative
     */
    public function applyBalanceChange(string $changeAmount): void
    {
        /** @var numeric-string $balance */
        $balance = $this->balance;
        /** @var numeric-string $change */
        $change = $changeAmount;

        $newBalance = bcadd($balance, $change, 2);

        if (bccomp($newBalance, '0', 2) < 0) {
            throw new \DomainException('Balance cannot be negative');
        }

        $this->balance = $newBalance;
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * Get balance as float for JSON serialization.
     */
    public function getBalanceAsFloat(): float
    {
        return (float) $this->balance;
    }
}
