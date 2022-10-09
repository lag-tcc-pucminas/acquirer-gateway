<?php

namespace App\Acquirer\Dto;

class AuthorizeResultData extends AcquirerResultData
{
    public bool $authorized;

    public string $acquirerReference;

    public ?string $nsu;

    public ?string $authorizationCode;
}
