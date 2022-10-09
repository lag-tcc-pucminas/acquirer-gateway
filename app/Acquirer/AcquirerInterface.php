<?php

namespace App\Acquirer;

use App\Acquirer\Dto\AcquirerResult;
use App\Dto\PaymentContext;
use App\Model\Payment;

interface AcquirerInterface
{
    public function authorize(Payment $payment, PaymentContext $paymentContext): AcquirerResult;
}
