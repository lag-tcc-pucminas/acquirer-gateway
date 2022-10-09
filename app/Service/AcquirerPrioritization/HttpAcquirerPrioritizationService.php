<?php

namespace App\Service\AcquirerPrioritization;

use App\Exception\AcquirerPrioritizationException;
use App\Service\AcquirerPrioritizationService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;

class HttpAcquirerPrioritizationService implements AcquirerPrioritizationService
{
    public function __construct(private Client $client)
    {
    }

    /**
     * @throws GuzzleException
     * @throws AcquirerPrioritizationException
     */
    public function getPrioritization(string $brand, int $installment): AcquirerPrioritization
    {
        $query = [
            'brand' => $brand,
            'installment' => $installment
        ];

        try {
            $response = $this->client->get(sprintf('acquirer-prioritization?%s', http_build_query($query)));
        } catch (ConnectException $exception) {
            throw new AcquirerPrioritizationException();
        }

        if ($response->getStatusCode() !== 200) {
            throw new AcquirerPrioritizationException();
        }

        $prioritization = array_map(
            fn ($item) => ['priority' => $item['priority'], 'acquirer' => $item['acquirer']['name']],
            json_decode($response->getBody(), true)
        );

        usort($prioritization, function (array $previous, array $current) {
            return $previous['priority'] - $current['priority'];
        });

        return AcquirerPrioritization::fromArray($prioritization);
    }
}
