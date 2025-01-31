<?php

namespace Ubtashton\Cbsync;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Exception\ClientException;

class ContactBase
{
    private HttpClientInterface $client;
    private string $authToken;
    private string $clientId;
    private string $clientSecret;
    private string $baseUrl = 'https://cb-entries.ubtglobal.com';
    private string $authUrl = 'https://auth.ubtglobal.com';

    public function __construct(string $clientId, string $clientSecret)
    {
        $this->client = HttpClient::create();
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    private function authenticate(): void
    {
        try {
            $response = $this->client->request('POST', "{$this->authUrl}/api/Token/GenerateMachineToken", [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode([
                    'clientId' => $this->clientId,
                    'clientSecret' => $this->clientSecret
                ])
            ]);
            $data = $response->toArray();
            $this->authToken = $data['accessToken'];
        } catch (ClientException $e) {
            throw new \RuntimeException('Authentication failed: ' . $e->getMessage());
        }
    }

    private function request(string $method, string $endpoint, array $options = []): array
    {
        if (empty($this->authToken)) {
            $this->authenticate();
        }

        $options['headers'] = array_merge(
            $options['headers'] ?? [],
            ['Authorization' => "Bearer {$this->authToken}"]
        );

        try {
            $response = $this->client->request($method, "{$this->baseUrl}{$endpoint}", $options);
            return $response->toArray();
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 401) {
                $this->authenticate();
                return $this->request($method, $endpoint, $options);
            }
            throw $e;
        }
    }

    public function getCountries(int $page = 1, int $pageSize = 100): array
    {
        return $this->request('GET', '/Location/Countries', [
            'query' => [
                'pageNumber' => $page,
                'pageSize' => $pageSize
            ]
        ]);
    }

    public function getLocalities(string $countryCode, int $page = 1, int $pageSize = 100): array
    {
        return $this->request('GET', "/Location/Localities", [
            'query' => [
                'country' => $countryCode,
                'pageNumber' => $page,
                'pageSize' => $pageSize
            ]
        ]);
    }

    public function getSubdivisions(string $countryCode, $locality, int $page = 1, int $pageSize = 100): array
    {
        return $this->request('GET', "/Location/Subdivisions", [
            'query' => [
                'country' => $countryCode,
                'locality' => $locality,
                'pageNumber' => $page,
                'pageSize' => $pageSize
            ]
        ]);
    }

    public function getContacts(
        ?string $countryCode = null,
        ?string $locality = null,
        ?string $subdivision = null,
        
        int $page = 1,
        int $pageSize = 100
    ): array {
        $query = [
            'pageNumber' => $page,
            'pageSize' => $pageSize
        ];

        if ($locality) {
            $query['locality'] = $locality;
        }

        if ($subdivision) {
            $query['subdivision'] = $subdivision;
        }

        if ($countryCode) {
            $query['country'] = $countryCode;
        }

        return $this->request('GET', "/Entries", [
            'query' => $query
        ]);
    }
}
