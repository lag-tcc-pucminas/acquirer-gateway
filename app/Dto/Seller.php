<?php

namespace App\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class Seller extends DataTransferObject
{
    public string $name;

    public string $document;

    public string $email;

    public string $phone;

    public string $mcc;

    public Address $address;
}
