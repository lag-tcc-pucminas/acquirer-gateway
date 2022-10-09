<?php

namespace App\Controller;

use App\Controller\Documentation\AcquirerPrioritizationControllerDocumentation;
use App\Service\AcquirerPrioritization\CacheAcquirerPrioritizationService;
use Hyperf\HttpServer\Contract\RequestInterface as Request;
use Hyperf\HttpServer\Contract\ResponseInterface as Response;
use Psr\Http\Message\ResponseInterface;

class AcquirerPrioritizationController implements AcquirerPrioritizationControllerDocumentation
{
    public function __construct(
        private CacheAcquirerPrioritizationService $service
    ) {
    }

    public function delete(Request $request, Response $response): ResponseInterface
    {
        $this->service->delete($request->route('brand'));
        return $response->json([])->withStatus(200);
    }
}
