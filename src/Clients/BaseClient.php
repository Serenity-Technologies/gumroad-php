<?php

/**
 * @author Kwadwo Kyeremeh <kyerematics@gmail.com>
 * @link https://github.com/serenity-technologies/gumroad-php
 * @license MIT
 */

namespace Gumroad\Clients;

use Gumroad\Exceptions\GumroadException;
use Illuminate\Support\Facades\Http;

class BaseClient
{
    protected string $accessToken;
    protected array $config;
    protected string $baseUrl;
    protected int $timeout = 30;
    protected int $connectTimeout = 10;

    public function __construct(array $config)
    {
        $this->config = $config;
        $accessToken = $config['access_token'] ?? null;
        $this->accessToken = $accessToken;
        $this->baseUrl = $config['base_url'] ?? 'https://api.gumroad.com/v2';
        $this->timeout = $config['http']['timeout'] ?? 30;
        $this->connectTimeout = $config['http']['connect_timeout'] ?? 10;
    }

    /**
     * @throws GumroadException
     */
    public function get(string $uri, array $query = []): array
    {
        return $this->request('get', $uri, $query);
    }

    /**
     * @throws GumroadException
     */
    public function post(string $uri, array $data = []): array
    {
        return $this->request('post', $uri, $data);
    }

    /**
     * @throws GumroadException
     */
    public function put(string $uri, array $data = []): array
    {
        return $this->request('put', $uri, $data);
    }

    /**
     * @throws GumroadException
     */
    public function delete(string $uri, array $data = []): array
    {
        return $this->request('delete', $uri, $data);
    }

    /**
     * @throws GumroadException
     */
    private function request(string $method, string $uri, array $data = []): array
    {
        try {
            $request = Http::withToken($this->accessToken)
                ->withUserAgent('SerenityTechnologies\Gumroad')
                ->acceptJson()
                ->baseUrl($this->baseUrl)
                ->timeout($this->timeout);

            $response = match (strtolower($method)) {
                'get' => $request->get($uri, $data),
                'post' => $request->post($uri, $data),
                'put' => $request->put($uri, $data),
                'delete' => $request->delete($uri, $data),
                default => throw new GumroadException("Unsupported HTTP method: {$method}"),
            };

            if (!$response->successful()) {
                throw new GumroadException($response->json('message') ?? 'API request failed: ' . $response->status());
            }

            $responseData = $response->json();

            if (!($responseData['success'] ?? false)) {
                throw new GumroadException($responseData['message'] ?? 'API request failed');
            }

            return $responseData;
        } catch (\Exception $e) {
            throw new GumroadException($e->getMessage(), $e->getCode(), $e);
        }
    }
}