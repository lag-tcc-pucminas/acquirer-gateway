<?php

namespace App\Controller\Documentation;

use Hyperf\HttpServer\Contract\RequestInterface as Request;
use Hyperf\HttpServer\Contract\ResponseInterface as Response;
use Psr\Http\Message\ResponseInterface;
use App\Http\Request\AuthorizeRequest;
use OpenApi\Annotations as OA;

interface PaymentControllerDocumentation
{
    /**
     * @OA\Post(
     *   path="/payment",
     *   operationId="Authorize Payment",
     *   summary="Authorize Payment",
     *   description="Authorize Payment",
     *   tags={"Authorize Payment"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/AuthorizeRequest")
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Created Payment Data",
     *     @OA\JsonContent(ref="#/components/schemas/PaymentResource")
     *   ),
     *   @OA\Response(
     *     response=409,
     *     description="The given idempotency id has already been used.",
     *     @OA\JsonContent(ref="#/components/schemas/PaymentResource")
     *   ),
     *   @OA\Response(response=422, description="Invalid Request"),
     *   @OA\Response(response=424, description="An error occurred during the acquirer prioritization query.")
     * )
     */
    public function authorize(AuthorizeRequest $request, Response $response): ResponseInterface;

    /**
     * @OA\Get(
     *   path="/payment/{idempotency_id}",
     *   operationId="Get Payment By Idempotency Id",
     *   summary="Get Payment By Idempotency Id",
     *   description="Get Payment By Idempotency Id",
     *   tags={"Get Payment By Idempotency Id"},
     *   @OA\Parameter(
     *     name="idempotency_id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *       type="string",
     *       example="72d5d38a-dd57-4f36-91b5-6cf767f21049"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Found Payment Data",
     *     @OA\JsonContent(ref="#/components/schemas/PaymentResource")
     *   ),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function find(Request $request, Response $response): ResponseInterface;
}
