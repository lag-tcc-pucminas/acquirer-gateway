<?php

namespace Test\Integration;

use App\Enum\AcquirerEnum;
use Hyperf\Redis\Redis;
use Hyperf\Testing\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class CloseCircuitTest extends TestCase
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
        $this->redis->set("circuit-breaker:$acquirer:$acquirer:open", 1);

        /** @var ResponseInterface $response */
        $response = $this->client->request('DELETE', sprintf('/circuit/%s', $acquirer));

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertFalse((bool) $this->redis->exists("circuit-breaker:$acquirer:$acquirer:open"));
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
        $response = $this->client->request('DELETE', '/circuit/fake');
        $this->assertEquals(404, $response->getStatusCode());
    }
}