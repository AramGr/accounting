<?php

namespace App\Service;

use App\Entity\RequestLog;
use App\Enum\CreditActionType;
use App\Repository\RequestLogRepositoryInterface;

class CreditService
{
    private const CREDIT_AMOUNT = '1';

    public function __construct(
        private readonly ProviderClient $providerClient,
        private readonly RequestLogRepositoryInterface $requestLogRepository
    ) {
    }

    /**
     * Add credit (amount = 1).
     *
     * @return array<string, mixed>
     */
    public function addCredit(): array
    {
        $amount = self::CREDIT_AMOUNT;
        $action = CreditActionType::ADD;
        $success = false;

        try {
            $result = $this->providerClient->updateBalance($amount, $action->value);
            $success = true;

            $log = new RequestLog($action->value, $amount, $success);
            $this->requestLogRepository->save($log);

            return $result;
        } catch (\Exception $e) {
            $log = new RequestLog($action->value, $amount, $success);
            $this->requestLogRepository->save($log);

            throw $e;
        }
    }

    /**
     * Remove credit (amount = 1).
     *
     * @return array<string, mixed>
     */
    public function removeCredit(): array
    {
        $amount = self::CREDIT_AMOUNT;
        $action = CreditActionType::REMOVE;
        $success = false;

        try {
            $result = $this->providerClient->updateBalance($amount, $action->value);
            $success = true;

            $log = new RequestLog($action->value, $amount, $success);
            $this->requestLogRepository->save($log);

            return $result;
        } catch (\Exception $e) {
            $log = new RequestLog($action->value, $amount, $success);
            $this->requestLogRepository->save($log);

            throw $e;
        }
    }
}
