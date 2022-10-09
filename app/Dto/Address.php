<?php

namespace App\Dto;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class Address extends DataTransferObject
{
    #[MapFrom('zip_code')]
    public string $zipCode;

    public string $country;

    public string $state;

    public string $city;

    public string $neighborhood;

    public string $street;

    public string $number;

    public ?string $complement;
}
