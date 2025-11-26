<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

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
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->balance = '0.00';
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBalance(): string
    {
        return $this->balance;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Apply a balance change
     *
     * @throws \DomainException if resulting balance would be negative
     */
    public function applyBalanceChange(string $changeAmount): void
    {
        $newBalance = bcadd($this->balance, $changeAmount, 2);

        if (bccomp($newBalance, '0', 2) < 0) {
            throw new \DomainException('Balance cannot be negative');
        }

        $this->balance = $newBalance;
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * Get balance as float for JSON serialization
     */
    public function getBalanceAsFloat(): float
    {
        return (float) $this->balance;
    }
}
