<?php

namespace App\Service;

use InvalidArgumentException;
use LeoCarmo\CircuitBreaker\CircuitBreaker;

class CircuitBreakerService
{
    /**
     * @param CircuitBreaker[] $acquirerCircuits
     */
    public function __construct(private array $acquirerCircuits)
    {
    }

    public function isAvailable(string $acquirer): bool
    {
        return $this->getCircuitByAcquirer($acquirer)->isAvailable();
    }

    public function getFailuresCounter(string $acquirer): int
    {
        return $this->getCircuitByAcquirer($acquirer)->getFailuresCounter();
    }

    public function openCircuit(string $acquirer): void
    {
        $this->getCircuitByAcquirer($acquirer)->openCircuit();
    }

    public function closeCircuit(string $acquirer): void
    {
        $this->getCircuitByAcquirer($acquirer)->success();
    }

    public function failure(string $acquirer): void
    {
        $this->getCircuitByAcquirer($acquirer)->failure();
    }

    public function success(string $acquirer): void
    {
        $this->getCircuitByAcquirer($acquirer)->success();
    }

    private function getCircuitByAcquirer(string $acquirer): CircuitBreaker
    {
        if (empty($this->acquirerCircuits[$acquirer])) {
            throw new InvalidArgumentException();
        }

        return $this->acquirerCircuits[$acquirer];
    }
}
