<?php

namespace App\Dto;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class Card extends DataTransferObject
{
    public string $holder;

    public string $brand;

    public string $pan;

    public string $cvv;

    #[MapFrom('expiry_date')]
    public string $expiryDate;
}
