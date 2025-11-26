<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ProviderClient
{
    private Client $client;

    public function __construct(string $providerBaseUrl)
    {
        $this->client = new Client([
            'base_uri' => $providerBaseUrl,
            'timeout' => 5.0,
            'http_errors' => false,
        ]);
    }

    /**
     * Update balance on Provider microservice
     * 
     * @throws \RuntimeException if request fails
     */
    public function updateBalance(string $amount, string $type): array
    {
        try {
            $response = $this->client->post('/account/balance', [
                'json' => [
                    'amount' => $amount,
                    'type' => $type,
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);

            if ($statusCode !== 200) {
                throw new \RuntimeException(
                    $body['error'] ?? 'Provider request failed',
                    $statusCode
                );
            }

            return $body;
        } catch (GuzzleException $e) {
            throw new \RuntimeException('Failed to communicate with Provider: ' . $e->getMessage());
        }
    }
}
