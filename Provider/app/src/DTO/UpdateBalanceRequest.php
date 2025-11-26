<?php

namespace App\DTO;

use App\Enum\BalanceOperationType;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateBalanceRequest
{
    #[Assert\NotBlank(message: 'Amount is required')]
    #[Assert\Positive(message: 'Amount must be positive')]
    #[Assert\Type(type: 'numeric', message: 'Amount must be numeric')]
    public string $amount;

    #[Assert\NotBlank(message: 'Type is required')]
    #[Assert\Choice(choices: ['add', 'remove'], message: 'Type must be either "add" or "remove"')]
    public string $type;

    public function getOperationType(): BalanceOperationType
    {
        return BalanceOperationType::from($this->type);
    }
}
