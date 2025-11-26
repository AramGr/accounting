<?php

namespace App\Entity;

use App\Repository\RequestLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestLogRepository::class)]
#[ORM\Table(name: 'request_logs')]
#[ORM\Index(name: 'IDX_request_logs_created_at', columns: ['created_at'])]
#[ORM\Index(name: 'IDX_request_logs_action', columns: ['action'])]
class RequestLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 10, columnDefinition: "ENUM('add', 'remove')")]
    private string $action;

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2)]
    private string $amount;

    #[ORM\Column(type: 'boolean')]
    private bool $success;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(
        string $action,
        string $amount,
        bool $success
    ) {
        $this->action = $action;
        $this->amount = $amount;
        $this->success = $success;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
