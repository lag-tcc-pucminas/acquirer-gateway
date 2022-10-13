<?php

namespace App\Enum;

class PaymentAttemptStatusEnum
{
    public const PENDING = 'PENDING';
    public const SUCCEEDED = 'SUCCEEDED';
    public const FAILED = 'FAILED';
    public const SKIPPED = 'SKIPPED';
}
