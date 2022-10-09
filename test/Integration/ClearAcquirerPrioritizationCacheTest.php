<?php

namespace Test\Integration;

use App\Enum\BrandEnum;
use Hyperf\Redis\Redis;
use Hyperf\Testing\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class ClearAcquirerPrioritizationCacheTest extends TestCase
{
    private Client $client;
    private Redis $redis;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = make(Client::class);
        $this->redis = make(Redis::class);
    }

    /**
     * @dataProvider brandProvider
     */
    public function testClearCache(string $brand): void
    {
        $this->redis->hSet("acquirer_prioritization:$brand", 'installment_1', '{}');

        /** @var ResponseInterface $response */
        $response = $this->client->request('DELETE', sprintf('/acquirer-prioritization/%s', $brand));

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertFalse((bool) $this->redis->exists("acquirer_prioritization:$brand"));
    }

    public function brandProvider(): array
    {
        return [
            BrandEnum::VISA => [BrandEnum::VISA],
            BrandEnum::MASTERCARD => [BrandEnum::MASTERCARD],
            BrandEnum::ELO => [BrandEnum::ELO],
            BrandEnum::HIPERCARD => [BrandEnum::HIPERCARD],
            BrandEnum::AMEX => [BrandEnum::AMEX],
        ];
    }
}