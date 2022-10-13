<?php

namespace App\Controller\Documentation;

use Hyperf\HttpServer\Contract\RequestInterface as Request;
use Hyperf\HttpServer\Contract\ResponseInterface as Response;
use Psr\Http\Message\ResponseInterface;
use OpenApi\Annotations as OA;

interface AcquirerPrioritizationControllerDocumentation
{
    /**
     * @OA\Delete(
     *      path="/acquirer-prioritization/{brand}",
     *      operationId="Delete Acquirer Prioritization Cache",
     *      summary="Delete Acquirer Prioritization Cache",
     *      description="Delete Acquirer Prioritization Cache",
     *      tags={"Acquirer Prioritization"},
     *      @OA\Parameter(
     *         name="brand",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="mastercard"
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Cache Successfully Removed."
     *       )
     *     )
     */
    public function delete(Request $request, Response $response): ResponseInterface;
}
