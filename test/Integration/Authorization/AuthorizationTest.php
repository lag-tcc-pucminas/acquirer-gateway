<?php

namespace Test\Integration\Authorization;

use Hyperf\Redis\Redis;
use Hyperf\Testing\Client;
use PHPUnit\Framework\TestCase;
use Test\Traits\RefreshDatabase;

abstract class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected Client $client;
    protected Redis $redis;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = make(Client::class);
        $this->redis = make(Redis::class);
        $this->refreshDatabase();
    }

    public function getAuthorizationRequest(array $extraParams = []): array
    {
        return array_replace_recursive([
            "idempotency_id" => "47a829b2-a61c-4ec1-a9ba-6931d59e277e",
            "value" => 1555,
            "installments" => 1,
            "mode" => "credit",
            "card" => [
                "brand" => "mastercard",
                "holder" => "Diogo Ricardo da Mata",
                "pan" => "5243071816971029",
                "cvv" => 753,
                "expiry_date" => "12/2025"
            ],
            "payer" => [
                "name" => "Diogo Ricardo da Mata",
                "document" => "12588354955",
                "email" => "diogo-damata84@example.com",
                "phone" => "(68) 99620-9319"
            ],
            "seller" => [
                "name" => "Melissa e César Fotografias Ltda",
                "document" => "12891765000148",
                "email" => "tesouraria@melissaecesarfotografiasltda.com.br",
                "phone" => "(11) 3811-6100",
                "mcc" => 5946,
                "address" => [
                    "zip_code" => "09894-050",
                    "country" => "BR",
                    "state" => "SP",
                    "city" => "São Bernardo do Campo",
                    "neighborhood" => "Jordanópolis",
                    "street" => "Rua Silas de Oliveira",
                    "number" => "341",
                    "complement" => "Casa"
                ]
            ]
        ], $extraParams);
    }

    protected function forceAcquirerPrioritization(
        string $brand,
        int    $installment,
        array  $acquirers
    ): void
    {
        $priority = 1;
        $prioritization = array_map(function ($acquirer) use ($priority, $acquirers) {
            return [
                'acquirer' => $acquirer,
                'priority' => $priority++
            ];
        }, $acquirers);

        $this->redis->hSet(
            "acquirer_prioritization:$brand",
            sprintf('installment_%d', $installment),
            json_encode($prioritization)
        );
    }

}