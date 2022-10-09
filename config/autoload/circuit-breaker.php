<?php

declare(strict_types=1);

return [
    'timeWindow' => 60, // Time for an open circuit (seconds)
    'failureRateThreshold' => 3, // Fail rate for open the circuit
    'intervalToHalfOpen' => 30,  // Half open time (seconds)
];