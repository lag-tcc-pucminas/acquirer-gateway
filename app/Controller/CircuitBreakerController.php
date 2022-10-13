<?php

namespace App\Controller;

use App\Controller\Documentation\CircuitBreakerControllerDocumentation;
use App\Facade\CircuitBreakerFacade;
use Hyperf\HttpServer\Contract\RequestInterface as Request;
use Hyperf\HttpServer\Contract\ResponseInterface as Response;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

class CircuitBreakerController implements CircuitBreakerControllerDocumentation
{
    public function __construct(
        private CircuitBreakerFacade $service
    ) {
    }

    public function getCircuitStatus(Request $request, Response $response): ResponseInterface
    {
        $acquirer = $request->route('acquirer');

        try {
            return $response->json([
                'is_available' => $this->service->isAvailable($acquirer),
                'failures_counter' => $this->service->getFailuresCounter($acquirer),
            ])->withStatus(200);
        } catch (InvalidArgumentException $exception) {
            return $response->json([])->withStatus(404);
        }
    }

    public function openCircuit(Request $request, Response $response): ResponseInterface
    {
        $acquirer = $request->route('acquirer');

        try {
            $this->service->openCircuit($acquirer);
            return $response->json([])->withStatus(200);
        } catch (InvalidArgumentException $exception) {
            return $response->json([])->withStatus(404);
        }
    }

    public function closeCircuit(Request $request, Response $response): ResponseInterface
    {
        $acquirer = $request->route('acquirer');

        try {
            $this->service->closeCircuit($acquirer);
            return $response->json([])->withStatus(200);
        } catch (InvalidArgumentException $exception) {
            return $response->json([])->withStatus(404);
        }
    }
}
