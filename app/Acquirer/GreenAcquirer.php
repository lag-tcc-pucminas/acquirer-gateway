<?php

namespace App\Acquirer;

use App\Dto\PaymentContext;
use App\Model\Payment;

class GreenAcquirer extends BaseAcquirer
{
    protected function shouldReturnError(Payment $payment, PaymentContext $paymentContext): bool
    {
        $valueDigits = str_split(strval($payment->value));

        $firstDigit = current($valueDigits);

        foreach ($valueDigits as $digit) {
            if ($digit != $firstDigit) {
                return false;
            }
        }

        return true;
    }
}
