<?php

namespace Test\Integration;

use App\Enum\AcquirerEnum;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Hyperf\Testing\Client;

class GetCircuitBreakerStatusTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = make(Client::class);
    }

    /**
     * @dataProvider acquirerProvider
     */
    public function testGetCircuitStatus(string $acquirer): void
    {
        /** @var ResponseInterface $response */
        $response = $this->client->request('GET', sprintf('/circuit/%s', $acquirer));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            "is_available" => true,
            "failures_counter" => 0
        ], json_decode($response->getBody(), true));
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
        $response = $this->client->request('GET', '/circuit/fake');
        $this->assertEquals(404, $response->getStatusCode());
    }
}