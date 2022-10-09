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

use Hyperf\HttpServer\Router\Router;

Router::addRoute('GET', '/', 'App\Controller\Documentation\DocumentationController@index');
Router::addRoute('GET', '/docs', 'App\Controller\Documentation\DocumentationController@docs');

Router::addRoute('GET' , '/health', 'App\Controller\HealthController@index');

Router::addRoute('GET', '/circuit/{acquirer}', 'App\Controller\CircuitBreakerController@getCircuitStatus');
Router::addRoute('POST', '/circuit/{acquirer}', 'App\Controller\CircuitBreakerController@openCircuit');
Router::addRoute('DELETE', '/circuit/{acquirer}', 'App\Controller\CircuitBreakerController@closeCircuit');

Router::addRoute('DELETE', '/acquirer-prioritization/{brand}', 'App\Controller\AcquirerPrioritizationController@delete');

Router::addRoute('POST', '/payment', 'App\Controller\PaymentController@authorize');
Router::addRoute('GET', '/payment/{idempotency_id}', 'App\Controller\PaymentController@find');