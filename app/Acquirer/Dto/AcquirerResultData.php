<?php

namespace App\Acquirer\Dto;

use Spatie\DataTransferObject\DataTransferObject;

abstract class AcquirerResultData extends DataTransferObject
{
    public string $code;

    public string $message;
}
