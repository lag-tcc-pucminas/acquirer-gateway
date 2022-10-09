<?php

namespace App\Http\Resource;

use App\Model\PaymentAcquirerAttempt;
use Hyperf\Resource\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
class PaymentAcquirerAttemptResource extends JsonResource
{
    /** @var PaymentAcquirerAttempt */
    public $resource;

    /**
     * @OA\Property(
     *   property="id",
     *   type="integer",
     *   example="1"
     * ),
     * @OA\Property(
     *   property="status",
     *   type="string",
     *   example="PENDING|SUCCEEDED|FAILED|SKIPPED"
     * ),
     * @OA\Property(
     *   property="acquirer",
     *   type="string",
     *   example="blue"
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
     * )
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->resource->id,
            'status' => $this->resource->status,
            'acquirer' => $this->resource->acquirer,
            'code' => $this->resource->acquirer_code,
            'message' => $this->resource->acquirer_message
        ];
    }
}
