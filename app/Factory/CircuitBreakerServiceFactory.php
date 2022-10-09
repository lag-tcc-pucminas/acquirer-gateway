<?php

namespace App\Factory;

use App\Enum\AcquirerEnum;
use App\Service\CircuitBreakerService;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Redis\Redis;
use LeoCarmo\CircuitBreaker\Adapters\RedisAdapter;
use LeoCarmo\CircuitBreaker\CircuitBreaker;
use Psr\Container\ContainerInterface;

class CircuitBreakerServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $circuitConfiguration = $config->get('circuit-breaker');

        $redis = $container->get(Redis::class);

        $circuits = [];
        foreach (AcquirerEnum::getValidValues() as $acquirer) {
            $circuitAdapter = new RedisAdapter($redis, $acquirer);
            $circuit = new CircuitBreaker($circuitAdapter, $acquirer);

            $circuit->setSettings($circuitConfiguration);

            $circuits[$acquirer] = $circuit;
        }

        return new CircuitBreakerService($circuits);
    }
}
