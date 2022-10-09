<?php

namespace App\Acquirer;

use App\Dto\PaymentContext;
use App\Enum\BrandEnum;
use App\Model\Payment;

class BlueAcquirer extends BaseAcquirer
{
    protected function shouldReturnError(Payment $payment, PaymentContext $paymentContext): bool
    {
        return !in_array($paymentContext->card->brand, [BrandEnum::MASTERCARD, BrandEnum::VISA]);
    }
}
