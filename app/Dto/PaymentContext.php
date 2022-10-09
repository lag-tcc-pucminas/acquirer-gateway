<?php

namespace App\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class PaymentContext extends DataTransferObject
{
    public Card $card;

    public Payer $payer;

    public Seller $seller;
}
