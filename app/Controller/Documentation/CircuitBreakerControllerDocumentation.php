<?php

namespace App\Controller\Documentation;

use Hyperf\HttpServer\Contract\RequestInterface as Request;
use Hyperf\HttpServer\Contract\ResponseInterface as Response;
use Psr\Http\Message\ResponseInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="CircuitStatus",
 *   @OA\Property(property="is_available", type="boolean", example=false),
 *   @OA\Property(property="failures_counter", type="integer", example=2)
 * )
 */
interface CircuitBreakerControllerDocumentation
{
    /**
     * @OA\Get(
     *   path="/circuit/{acquirer}",
     *   operationId="Get Acquirer Circuit Status",
     *   summary="Get Acquirer Circuit Status",
     *   description="Get Acquirer Circuit Status",
     *   tags={"Get Acquirer Circuit Status"},
     *   @OA\Parameter(
     *     name="acquirer",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *       type="string",
     *       example="green"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/CircuitStatus")
     *   ),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function getCircuitStatus(Request $request, Response $response): ResponseInterface;

    /**
     * @OA\Post(
     *   path="/circuit/{acquirer}",
     *   operationId="Open Acquirer Circuit",
     *   summary="Open Acquirer Circuit",
     *   description="Marks the acquirer as not available by opening the circuit.",
     *   tags={"Open Acquirer Circuit"},
     *   @OA\Parameter(
     *     name="acquirer",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *       type="string",
     *       example="green"
     *     )
     *   ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function openCircuit(Request $request, Response $response): ResponseInterface;

    /**
     * @OA\Delete(
     *   path="/circuit/{acquirer}",
     *   operationId="Close Acquirer Circuit",
     *   summary="Close Acquirer Circuit",
     *   description="Marks the acquirer as available by closing the circuit.",
     *   tags={"Close Acquirer Circuit"},
     *   @OA\Parameter(
     *     name="acquirer",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *       type="string",
     *       example="green"
     *     )
     *   ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function closeCircuit(Request $request, Response $response): ResponseInterface;
}
