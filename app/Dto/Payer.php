<?php

namespace App\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class Payer extends DataTransferObject
{
    public string $name;

    public string $document;

    public string $email;

    public string $phone;
}
