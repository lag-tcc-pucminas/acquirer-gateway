<?php

namespace App\Service;

use App\Service\AcquirerPrioritization\AcquirerPrioritization;

interface AcquirerPrioritizationService
{
    public function getPrioritization(string $brand, int $installment): AcquirerPrioritization;
}
