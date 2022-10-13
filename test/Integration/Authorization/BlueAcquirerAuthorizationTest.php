<?php

namespace Test\Integration\Authorization;

use App\Enum\AcquirerEnum;
use App\Enum\BrandEnum;
use App\Enum\PaymentAttemptStatusEnum;
use App\Enum\PaymentStatusEnum;
use Psr\Http\Message\ResponseInterface;

class BlueAcquirerAuthorizationTest extends AuthorizationTest
{
    public function testAuthorize(): void
    {
        $this->forceAcquirerPrioritization(BrandEnum::MASTERCARD, 1, [AcquirerEnum::BLUE]);

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
        $this->assertEquals(AcquirerEnum::BLUE, $responseBody['acquirer']);
        $this->assertEquals(PaymentAttemptStatusEnum::SUCCEEDED, $responseBody['attempts'][0]['status']);
    }

    public function testAcquirerShouldErrorWhenCardBrandNotIsVisaOrMastercard(): void
    {
        $this->forceAcquirerPrioritization(BrandEnum::ELO, 1, [AcquirerEnum::BLUE]);

        /** @var ResponseInterface $response */
        $response = $this->client->request('POST', '/payment', [
            'json' => $this->getAuthorizationRequest([
                'card' => [
                    'brand' => BrandEnum::ELO
                ]
            ]),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);

        $this->assertEquals(PaymentStatusEnum::FAILED, $responseBody['status']);
        $this->assertEquals(AcquirerEnum::BLUE, $responseBody['acquirer']);
        $this->assertEquals(PaymentAttemptStatusEnum::FAILED, $responseBody['attempts'][0]['status']);

        $this->assertEquals(1, $this->redis->get('circuit-breaker:blue:blue:failures'));
    }
}