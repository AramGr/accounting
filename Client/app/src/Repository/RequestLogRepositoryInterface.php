<?php

namespace App\Repository;

use App\Entity\RequestLog;

interface RequestLogRepositoryInterface
{
    public function save(RequestLog $log): void;
}
