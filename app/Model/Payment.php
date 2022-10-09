<?php

namespace App\Model;

use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;
use Hyperf\Database\Model\Relations\HasMany;

/**
 * @property int $id
 * @property string idempotency_id
 * @property string status
 * @property int installments
 * @property string acquirer
 * @property ?string acquirer_reference
 * @property ?string nsu
 * @property ?string authorization_code
 * @property string mode
 * @property string mcc
 * @property int value
 * @property Collection acquirerAttempts
 */
class Payment extends Model
{
    protected $table = 'payments';

    protected $guarded = ['id'];

    public function acquirerAttempts(): HasMany
    {
        return $this->hasMany(PaymentAcquirerAttempt::class, 'payment_id', 'id');
    }

    public function getCurrentAttempt(): ?PaymentAcquirerAttempt
    {
        return $this->acquirerAttempts->where('acquirer', $this->acquirer)->first();
    }
}
