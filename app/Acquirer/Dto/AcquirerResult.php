<?php

namespace App\Acquirer\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class AcquirerResult extends DataTransferObject
{
    public bool $succeeded;

    public ?AcquirerResultData $data;

    public function getData(): ?AcquirerResultData
    {
        return $this->data;
    }
}
