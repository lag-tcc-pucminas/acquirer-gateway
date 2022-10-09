<?php

namespace App\Http\Request;

use App\Enum\BrandEnum;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
class AuthorizeRequest extends BaseRequest
{
    /**
     * @OA\Property(
     *   property="idempotency_id",
     *   type="string",
     *   example="3a2567c0-e7a9-4b29-b07a-bbdfd337a47d"
     * ),
     * @OA\Property(
     *   property="value",
     *   type="integer",
     *   example="10000",
     *   description="Payment amount in cents"
     * ),
     * @OA\Property(
     *   property="installments",
     *   type="integer",
     *   example="1",
     *   description="Number of installments"
     * ),
     * @OA\Property(
     *   property="mode",
     *   type="string",
     *   example="credit|debit",
     * )
     * @OA\Property(
     *   property="card",
     *   type="object",
     *   @OA\Property(
     *     property="brand",
     *     type="string",
     *     example="visa"
     *   ),
     *   @OA\Property(
     *     property="holder",
     *     type="string",
     *     example="John",
     *     description="Card Holder Name",
     *   ),
     *   @OA\Property(
     *     property="pan",
     *     type="string",
     *     example="4111111111111111"
     *   ),
     *   @OA\Property(
     *     property="cvv",
     *     type="integer",
     *     example="134"
     *   ),
     *   @OA\Property(
     *     property="expiry_date",
     *     type="string",
     *     example="12/2025"
     *   )
     * ),
     * @OA\Property(
     *   property="payer",
     *   type="object",
     *   @OA\Property(
     *     property="name",
     *     type="string",
     *     example="John"
     *   ),
     *   @OA\Property(
     *     property="document",
     *     type="string",
     *     example="65591145035"
     *   ),
     *   @OA\Property(
     *     property="email",
     *     type="string",
     *     example="john@fake.com"
     *   ),
     *   @OA\Property(
     *     property="phone",
     *     type="string",
     *     example="(11) 11111-1111"
     *   )
     * ),
     * @OA\Property(
     *   property="seller",
     *   type="object",
     *   @OA\Property(
     *     property="name",
     *     type="string",
     *     example="Joaquim's Bakery"
     *   ),
     *   @OA\Property(
     *     property="document",
     *     type="string",
     *     example="12891765000148"
     *   ),
     *   @OA\Property(
     *     property="email",
     *     type="string",
     *     example="joaquim@bakery.com"
     *   ),
     *   @OA\Property(
     *     property="phone",
     *     type="string",
     *     example="(11) 3811-6100"
     *   ),
     *   @OA\Property(
     *     property="mcc",
     *     type="integer",
     *     example="5946"
     *   ),
     *   @OA\Property(
     *     property="address",
     *     type="object",
     *     @OA\Property(
     *       property="zip_code",
     *       type="string",
     *       example="09894-050"
     *     ),
     *     @OA\Property(
     *       property="country",
     *       type="string",
     *       example="BR"
     *     ),
     *     @OA\Property(
     *       property="state",
     *       type="string",
     *       example="SP"
     *     ),
     *     @OA\Property(
     *       property="city",
     *       type="string",
     *       example="São Bernardo do Campo"
     *     ),
     *     @OA\Property(
     *       property="neighborhood",
     *       type="string",
     *       example="Jordanópolis"
     *     ),
     *     @OA\Property(
     *       property="street",
     *       type="string",
     *       example="Rua Silas de Oliveira"
     *     ),
     *     @OA\Property(
     *       property="number",
     *       type="string",
     *       example="341"
     *     ),
     *     @OA\Property(
     *       property="complement",
     *       type="string",
     *       example="Next to the market"
     *     )
     *   )
     * )
     */
    protected function getRules(): array
    {
        return [
            "idempotency_id" => "required|uuid",
            "value" => "required|integer",
            "installments" => "required|integer|between:1,12",
            "mode" => "required|string|in:credit,debit",

            # Card
            "card" => "required|filled",
            "card.holder" => "required|string",
            "card.brand" => "required|string|in:" . implode(',', BrandEnum::getValidValues()),
            "card.pan" => "required|string",
            "card.cvv" => "required|integer",
            "card.expiry_date" => "required|string",

            # Payer
            "payer" => "required|filled",
            "payer.document" => "required|string",
            "payer.email" => "required|email",
            "payer.phone" => "required|string",

            #Seller
            "seller" => "required|filled",
            "seller.name" => "required|string",
            "seller.document" => "required|string",
            "seller.email" => "required|email",
            "seller.phone" => "required|string",
            "seller.mcc" => "required|integer",

            # Seller Address
            "seller.address" => "required|filled",
            "seller.address.zip_code" => "required|string",
            "seller.address.country" => "required|string",
            "seller.address.state" => "required|string",
            "seller.address.city" => "required|string",
            "seller.address.neighborhood" => "required|string",
            "seller.address.street" => "required|string",
            "seller.address.number" => "required|string",
            "seller.address.complement" => "nullable|string",
        ];
    }
}
