<?php

namespace App\Factory;

use App\Service\AcquirerPrioritization\CacheAcquirerPrioritizationService;
use App\Service\AcquirerPrioritization\HttpAcquirerPrioritizationService;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\TransferStats;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Guzzle\PoolHandler;
use Hyperf\Redis\Redis;
use Hyperf\Utils\Coroutine;
use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerInterface;

class CacheAcquirerPrioritizationServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class)->get('guzzle.acquiring-rules');

        $logger = $container->get(LoggerFactory::class)->get('acquiring-rules');

        $client = make(Client::class, [
            'config' => array_merge($config['client'], [
                'handler' => $this->createHandlerStack($config),
                'on_stats' => function (TransferStats $stats) use ($logger) {
                    $logger->info('Request to acquiring-rules', [
                        'url' => $stats->getRequest()->getUri()->getPath(),
                        'method' => $stats->getRequest()->getMethod(),
                        'headers' => $stats->getRequest()->getHeaders(),
                        'body' => json_decode($stats->getRequest()->getBody(), true),
                    ]);

                    if ($stats->getResponse()) {
                        $logger->info('Response from acquiring-rules', [
                            'status' => $stats->getResponse()->getStatusCode(),
                            'headers' => $stats->getResponse()->getHeaders(),
                            'body' => json_decode($stats->getResponse()->getBody(), true)
                        ]);
                    }
                }
            ])
        ]);

        $httpProvider = new HttpAcquirerPrioritizationService($client);

        $redis = $container->get(Redis::class);

        return new CacheAcquirerPrioritizationService($redis, $httpProvider);
    }

    private function createHandlerStack(array $config): HandlerStack
    {
        if (!Coroutine::inCoroutine() || !isset($config['pool'])) {
            return HandlerStack::create();
        }

        $poolHandler = make(PoolHandler::class, [
            'option' => [
                'max_connections' => 10,
            ],
        ]);

        return HandlerStack::create($poolHandler);
    }
}
