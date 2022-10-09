<?php

namespace App\Acquirer;

use App\Dto\PaymentContext;
use App\Model\Payment;

class RedAcquirer extends BaseAcquirer
{
    protected function shouldReturnError(Payment $payment, PaymentContext $paymentContext): bool
    {
        return $payment->installments > 6;
    }
}
