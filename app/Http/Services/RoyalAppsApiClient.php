<?php

namespace App\Http\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class RoyalAppsApiClient
{
    private Client $client;
    private ?string $token = null;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://candidate-testing.com/api/v2/',
            'timeout' => 30,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function login(string $email, string $password): ?array
    {
        try {
            $response = $this->client->post('token', [
                'json' => [
                    'email' => $email,
                    'password' => $password,
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['token_key'])) {
                return $data;
            }

            return null;
        } catch (RequestException $e) {
            Log::error('Login failed: ' . $e->getMessage());
            return null;
        }
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    private function getHeaders(): array
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        if ($this->token) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        return $headers;
    }

    public function getAuthors(array $params = []): ?array
    {
        try {
            $response = $this->client->get('authors', [
                'headers' => $this->getHeaders(),
                'query' => array_merge([
                    'orderBy' => 'id',
                    'direction' => 'ASC',
                    'limit' => 100,
                    'page' => 1
                ], $params)
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Failed to fetch authors: ' . $e->getMessage());
            return null;
        }
    }

    public function getAuthor(int $id): ?array
    {
        try {
            $response = $this->client->get("authors/{$id}", [
                'headers' => $this->getHeaders()
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error("Failed to fetch author {$id}: " . $e->getMessage());
            return null;
        }
    }

    public function deleteAuthor(int $id): bool
    {
        try {
            $response = $this->client->delete("authors/{$id}", [
                'headers' => $this->getHeaders()
            ]);

            return $response->getStatusCode() === 204;
        } catch (RequestException $e) {
            Log::error("Failed to delete author {$id}: " . $e->getMessage());
            return false;
        }
    }

    public function createAuthor(array $data): ?array
    {
        try {
            $response = $this->client->post('authors', [
                'headers' => $this->getHeaders(),
                'json' => $data
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Failed to create author: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteBook(int $id): bool
    {
        try {
            $response = $this->client->delete("books/{$id}", [
                'headers' => $this->getHeaders()
            ]);

            return $response->getStatusCode() === 204;
        } catch (RequestException $e) {
            Log::error("Failed to delete book {$id}: " . $e->getMessage());
            return false;
        }
    }

    public function createBook(array $data): ?array
    {
        try {
            $response = $this->client->post('books', [
                'headers' => $this->getHeaders(),
                'json' => $data
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Failed to create book: ' . $e->getMessage());
            return null;
        }
    }

    public function getProfile(): ?array
    {
        try {
            $response = $this->client->get('me', [
                'headers' => $this->getHeaders()
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Failed to fetch profile: ' . $e->getMessage());
            return null;
        }
    }
}
