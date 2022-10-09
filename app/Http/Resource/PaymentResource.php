<?php

namespace App\Http\Resource;

use App\Model\Payment;
use Hyperf\Resource\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
class PaymentResource extends JsonResource
{
    /** @var Payment */
    public $resource;

    /**
     * @OA\Property(
     *   property="id",
     *   type="integer",
     *   example="1"
     * ),
     * @OA\Property(
     *   property="idempotency_id",
     *   type="string",
     *   example="65288618-0d8e-40d6-a074-5ad0b4f8b459"
     * ),
     * @OA\Property(
     *   property="status",
     *   type="string",
     *   nullable=true,
     *   example="INITIAL|NOT_AUTHORIZED|AUTHORIZED|FAILED"
     * ),
     * @OA\Property(
     *   property="code",
     *   type="string",
     *   nullable=true,
     *   example="00|N7|51"
     * ),
     * @OA\Property(
     *   property="message",
     *   type="string",
     *   nullable=true,
     *   example="Authorized"
     * ),
     * @OA\Property(
     *   property="acquirer",
     *   type="string",
     *   example="blue"
     * ),
     * @OA\Property(
     *   property="acquirer_reference",
     *   type="string",
     *   nullable=true,
     *   example="49883da8-aaec-440b-9dc1-84797440f977"
     * ),
     * @OA\Property(
     *   property="nsu",
     *   type="string",
     *   nullable=true,
     *   example="917624"
     * ),
     * @OA\Property(
     *   property="authorization_code",
     *   type="string",
     *   nullable=true,
     *   example="819571782893"
     * ),
     * @OA\Property(
     *   property="mode",
     *   type="string",
     *   example="credit|debit"
     * ),
     * @OA\Property(
     *   property="mcc",
     *   type="integer",
     *   example="8999"
     * ),
     * @OA\Property(
     *   property="value",
     *   type="integer",
     *   example="10000"
     * ),
     * @OA\Property(
     *   property="installments",
     *   type="integer",
     *   example="12"
     * ),
     * @OA\Property(
     *  property="attempts",
     *  type="array",
     *  example={
     *    {"id":1,"status":"SKIPPED","acquirer":"blue","code":null,"message":null},
     *    {"id":2,"status":"FAILED","acquirer":"red","code":null,"message":null},
     *    {"id":3,"status":"SUCCEEDED","acquirer":"green","code":"00","message":"Authorized"}
     *  },
     *  @OA\Items(ref="#/components/schemas/PaymentAcquirerAttemptResource")
     * )
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->resource->id,
            'idempotency_id' => $this->resource->idempotency_id,
            'status' => $this->resource->status,
            'code' => $this->resource->getCurrentAttempt()->acquirer_code,
            'message' => $this->resource->getCurrentAttempt()->acquirer_message,
            'acquirer' => $this->resource->acquirer,
            'acquirer_reference' => $this->resource->acquirer_reference,
            'nsu' => $this->resource->nsu,
            'authorization_code' => $this->resource->authorization_code,
            'mode' => $this->resource->mode,
            'mcc' => $this->resource->mcc,
            'value' => $this->resource->value,
            'installments' => $this->resource->installments,
            'attempts' => PaymentAcquirerAttemptResource::collection($this->resource->acquirerAttempts)
        ];
    }
}
