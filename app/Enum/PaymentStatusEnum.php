<?php

namespace App\Enum;

class PaymentStatusEnum extends BaseEnum
{
    public const INITIAL = 'INITIAL';
    public const NOT_AUTHORIZED = 'NOT_AUTHORIZED';
    public const AUTHORIZED = 'AUTHORIZED';
    public const FAILED = 'FAILED';
}
