<?php

namespace App\Acquirer;

use App\Enum\AcquirerEnum;

class AcquirerFactory
{
    public function get(string $name): AcquirerInterface
    {
        return match ($name) {
            AcquirerEnum::GREEN => make(GreenAcquirer::class),
            AcquirerEnum::RED => make(RedAcquirer::class),
            AcquirerEnum::BLUE => make(BlueAcquirer::class)
        };
    }
}
