<?php

namespace App\Exception;

use App\Http\Resource\PaymentResource;
use App\Model\Payment;

class IdempotencyException extends HttpException
{
    public static function fromExistentPayment(Payment $payment): self
    {
        $self = new self();
        $self->message = 'The given idempotency id has already been used';
        $self->status = $self->code = 409;
        $self->response = PaymentResource::make($payment)->toArray();

        return $self;
    }
}
