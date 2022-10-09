<?php

namespace App\Model;

use Hyperf\Database\Model\Model;
use Hyperf\Database\Model\Relations\BelongsTo;

/**
 * @property int $id
 * @property string payment_id
 * @property string acquirer
 * @property string external_reference
 * @property string acquirer_code
 * @property string acquirer_message
 * @property string status
 * @property Payment payment
 */
class PaymentAcquirerAttempt extends Model
{
    protected $table = 'payment_acquirer_attempts';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }
}
