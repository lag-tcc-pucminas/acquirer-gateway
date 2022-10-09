<?php

namespace Test\Integration\Authorization;

use App\Enum\AcquirerEnum;
use App\Enum\BrandEnum;
use App\Enum\PaymentAttemptStatus;
use App\Enum\PaymentStatusEnum;
use Psr\Http\Message\ResponseInterface;

class AuthorizationWithFallbackTest extends AuthorizationTest
{
    public function testShouldUseFallbackWhenFirstAcquirerReturnedError(): void
    {
        $this->forceAcquirerPrioritization(
            BrandEnum::MASTERCARD, 1, [AcquirerEnum::GREEN, AcquirerEnum::BLUE]
        );

        /** @var ResponseInterface $response */
        $response = $this->client->request('POST', '/payment', [
            'json' => $this->getAuthorizationRequest(['value' => 9999]),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);

        $this->assertNotEquals(PaymentStatusEnum::FAILED, $responseBody['status']);
        $this->assertEquals(AcquirerEnum::BLUE, $responseBody['acquirer']);

        $attempts = $responseBody['attempts'];
        $this->assertCount(2, $attempts);

        list($firstAttempt, $secondAttempt) = $attempts;

        $this->assertEquals($firstAttempt['acquirer'], AcquirerEnum::GREEN);
        $this->assertEquals($firstAttempt['status'], PaymentAttemptStatus::FAILED);

        $this->assertEquals($secondAttempt['acquirer'], AcquirerEnum::BLUE);
        $this->assertEquals($secondAttempt['status'], PaymentAttemptStatus::SUCCEEDED);
    }

    public function testShouldUseFallbackWhenFirstAcquirerIsNotAvailable(): void
    {
        $this->redis->set("circuit-breaker:red:red:open", 1);

        $this->forceAcquirerPrioritization(
            BrandEnum::MASTERCARD,
            1,
            [AcquirerEnum::RED, AcquirerEnum::BLUE]
        );

        /** @var ResponseInterface $response */
        $response = $this->client->request('POST', '/payment', [
            'json' => $this->getAuthorizationRequest(),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);

        $this->assertNotEquals(PaymentStatusEnum::FAILED, $responseBody['status']);
        $this->assertEquals(AcquirerEnum::BLUE, $responseBody['acquirer']);

        $attempts = $responseBody['attempts'];
        $this->assertCount(2, $attempts);

        list($firstAttempt, $secondAttempt) = $attempts;

        $this->assertEquals($firstAttempt['acquirer'], AcquirerEnum::RED);
        $this->assertEquals($firstAttempt['status'], PaymentAttemptStatus::SKIPPED);

        $this->assertEquals($secondAttempt['acquirer'], AcquirerEnum::BLUE);
        $this->assertEquals($secondAttempt['status'], PaymentAttemptStatus::SUCCEEDED);
    }

    public function testShouldReturnConflictWhenIdempotencyIdAlreadyBeUsed(): void
    {
        $this->forceAcquirerPrioritization(
            BrandEnum::MASTERCARD, 1, [AcquirerEnum::GREEN]
        );

        /** @var ResponseInterface $response */
        $response = $this->client->request('POST', '/payment', [
            'json' => $this->getAuthorizationRequest(),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        /** @var ResponseInterface $response */
        $anotherResponse = $this->client->request('POST', '/payment', [
            'json' => $this->getAuthorizationRequest(),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        $this->assertEquals(409, $anotherResponse->getStatusCode());
    }
}