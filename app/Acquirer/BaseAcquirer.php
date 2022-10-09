<?php

namespace App\Acquirer;

use App\Acquirer\Dto\AcquirerResult;
use App\Acquirer\Dto\AuthorizeResultData;
use App\Dto\PaymentContext;
use App\Model\Payment;
use Faker\Factory;
use Faker\Generator;

class BaseAcquirer implements AcquirerInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function authorize(Payment $payment, PaymentContext $paymentContext): AcquirerResult
    {
        if ($this->shouldReturnError($payment, $paymentContext)) {
            return new AcquirerResult([
                'succeeded' => false
            ]);
        }

        $shouldApprove = $this->shouldApprove($payment, $paymentContext);

        return new AcquirerResult([
            'succeeded' => true,
            'data' => new AuthorizeResultData([
                'code' => $shouldApprove ? '00' : $this->faker->randomElement([
                    $this->faker->numberBetween(1, 89),
                    sprintf('%s%d', strtoupper($this->faker->randomLetter), $this->faker->randomDigit())
                ]),
                'message' => $shouldApprove ? 'Authorized' : 'Not Authorized',
                'authorized' => $shouldApprove,
                'acquirerReference' => $this->faker->uuid,
                'nsu' => ($shouldApprove) ? $this->faker->randomNumber(6) : null,
                'authorizationCode' => ($shouldApprove) ? $this->faker->randomNumber(6) : null
            ])
        ]);
    }

    private function shouldApprove(): bool
    {
        return rand(0, 1) > 0;
    }

    protected function shouldReturnError(Payment $payment, PaymentContext $paymentContext): bool
    {
        return false;
    }
}
