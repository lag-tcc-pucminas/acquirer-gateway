<?php

namespace App\Enum;

class PaymentAttemptStatus
{
    public const PENDING = 'PENDING';
    public const SUCCEEDED = 'SUCCEEDED';
    public const FAILED = 'FAILED';
    public const SKIPPED = 'SKIPPED';
}
