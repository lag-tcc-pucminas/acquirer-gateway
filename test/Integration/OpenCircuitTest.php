<?php

namespace Test\Integration;

use App\Enum\AcquirerEnum;
use Hyperf\Redis\Redis;
use Hyperf\Testing\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class OpenCircuitTest extends TestCase
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
     * @dataProvider acquirerProvider
     */
    public function testOpenCircuit(string $acquirer): void
    {
        /** @var ResponseInterface $response */
        $response = $this->client->request('POST', sprintf('/circuit/%s', $acquirer));

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertTrue((bool) $this->redis->exists("circuit-breaker:$acquirer:$acquirer:open"));
    }

    public function acquirerProvider(): array
    {
        return [
            AcquirerEnum::GREEN => [AcquirerEnum::GREEN],
            AcquirerEnum::RED => [AcquirerEnum::RED],
            AcquirerEnum::BLUE => [AcquirerEnum::BLUE],
        ];
    }

    public function testShouldReturn404WhenAcquirerNotExists():void
    {
        $response = $this->client->request('POST', '/circuit/fake');
        $this->assertEquals(404, $response->getStatusCode());
    }
}