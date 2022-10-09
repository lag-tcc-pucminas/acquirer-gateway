<?php

namespace Test\Integration\Authorization;

use App\Enum\AcquirerEnum;
use App\Enum\BrandEnum;
use App\Enum\PaymentAttemptStatus;
use App\Enum\PaymentStatusEnum;
use Psr\Http\Message\ResponseInterface;

class GreenAcquirerAuthorizationTest extends AuthorizationTest
{
    public function testAuthorize(): void
    {
        $this->forceAcquirerPrioritization(BrandEnum::MASTERCARD, 1, [AcquirerEnum::GREEN]);

        /** @var ResponseInterface $response */
        $response = $this->client->request('POST', '/payment', [
            'json' => $this->getAuthorizationRequest(),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);

        $this->assertTrue(in_array(
            $responseBody['status'],
            [PaymentStatusEnum::AUTHORIZED, PaymentStatusEnum::NOT_AUTHORIZED]
        ));
        $this->assertEquals(AcquirerEnum::GREEN, $responseBody['acquirer']);
        $this->assertEquals(PaymentAttemptStatus::SUCCEEDED, $responseBody['attempts'][0]['status']);
    }

    public function testAcquirerShouldErrorWhenValueOnlyContainsOneDistinctDigit(): void
    {
        $this->forceAcquirerPrioritization(BrandEnum::MASTERCARD, 1, [AcquirerEnum::GREEN]);

        /** @var ResponseInterface $response */
        $response = $this->client->request('POST', '/payment', [
            'json' => $this->getAuthorizationRequest(['value' => 1111]),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);

        $this->assertEquals(PaymentStatusEnum::FAILED, $responseBody['status']);
        $this->assertEquals(AcquirerEnum::GREEN, $responseBody['acquirer']);
        $this->assertEquals(PaymentAttemptStatus::FAILED, $responseBody['attempts'][0]['status']);

        $this->assertEquals(1, $this->redis->get('circuit-breaker:green:green:failures'));
    }
}