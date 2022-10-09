<?php

namespace App\Service\AcquirerPrioritization;

use App\Service\AcquirerPrioritizationService;
use Hyperf\Redis\Redis;

class CacheAcquirerPrioritizationService implements AcquirerPrioritizationService
{
    public function __construct(
        private Redis $redis,
        private HttpAcquirerPrioritizationService $proxy
    ) {
    }

    public function getPrioritization(string $brand, int $installment): AcquirerPrioritization
    {
        $cachedPrioritization = json_decode(
            $this->redis->hGet($this->getKey($brand), $this->getHashKey($installment)),
            true
        );

        if (!empty($cachedPrioritization)) {
            return AcquirerPrioritization::fromArray($cachedPrioritization);
        }

        $prioritization = $this->proxy->getPrioritization($brand, $installment);
        $this->store($brand, $installment, $prioritization);

        return $prioritization;
    }

    private function store(string $brand, int $installment, AcquirerPrioritization $prioritization): void
    {
        $this->redis->hSet(
            $this->getKey($brand),
            $this->getHashKey($installment),
            json_encode($prioritization->toArray())
        );
    }

    public function delete(string $brand): void
    {
        $this->redis->del($this->getKey($brand));
    }

    private function getKey(string $brand): string
    {
        return sprintf('acquirer_prioritization:%s', $brand);
    }

    private function getHashKey(int $installment): string
    {
        return sprintf('installment_%d', $installment);
    }
}
