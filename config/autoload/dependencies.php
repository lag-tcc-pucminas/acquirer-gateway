<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use App\Facade\CircuitBreakerFacade;
use App\Factory\CacheAcquirerPrioritizationServiceFactory;
use App\Factory\CircuitBreakerFacadeFactory;
use App\Service\AcquirerPrioritization\CacheAcquirerPrioritizationService;
use App\Service\AcquirerPrioritizationService;

return [
    # Bindings
    AcquirerPrioritizationService::class => CacheAcquirerPrioritizationServiceFactory::class,

    # Factories
    CircuitBreakerFacade::class => CircuitBreakerFacadeFactory::class,
    CacheAcquirerPrioritizationService::class => CacheAcquirerPrioritizationServiceFactory::class,
];
