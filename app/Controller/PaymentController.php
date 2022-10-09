<?php

namespace App\Controller;

use App\Controller\Documentation\PaymentControllerDocumentation;
use App\Http\Request\AuthorizeRequest;
use App\Http\Resource\PaymentResource;
use App\Repository\PaymentRepository;
use App\Service\PaymentService;
use Hyperf\HttpServer\Contract\ResponseInterface as Response;
use Hyperf\HttpServer\Contract\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface;

class PaymentController implements PaymentControllerDocumentation
{
    public function __construct(private PaymentService $service, private PaymentRepository $repository)
    {
    }

    public function authorize(AuthorizeRequest $request, Response $response): ResponseInterface
    {
        $payment = $this->service->createPayment($request);
        return $response->json(PaymentResource::make($payment))->withStatus(201);
    }

    public function find(Request $request, Response $response): ResponseInterface
    {
        $payment = $this->repository->getByIdempotencyId($request->route('idempotency_id'));

        if (!$payment) {
            return $response->json([])->withStatus(404);
        }

        return $response->json(PaymentResource::make($payment))->withStatus(200);
    }
}
