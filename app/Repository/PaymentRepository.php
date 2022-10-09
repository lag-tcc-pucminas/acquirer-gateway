<?php

namespace App\Repository;

use App\Model\Payment;
use Hyperf\DbConnection\Db;

class PaymentRepository
{
    public function __construct(private Payment $model)
    {
    }

    public function getByIdempotencyId(string $idempotencyId): ?Payment
    {
        return $this->model->newQuery()->where('idempotency_id', $idempotencyId)->first();
    }

    public function save(Payment $payment): Payment
    {
        return Db::transaction(function () use ($payment) {
            $payment->save();
            $payment->acquirerAttempts()->saveMany($payment->acquirerAttempts);

            return $payment;
        });
    }
}
