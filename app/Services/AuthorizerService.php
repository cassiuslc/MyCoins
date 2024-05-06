<?php

namespace App\Services;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Interfaces\AuthorizerServiceInterface;

class AuthorizerService implements AuthorizerServiceInterface
{
    protected $client;
    protected $url;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->url = env('AUTHORIZER_URL');
    }

    public function authorize(): bool
    {
        try {
            $response = $this->client->request('GET', $this->url);

            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                $data = json_decode($response->getBody()->getContents(), true);
                return $data['message'] === 'Autorizado'  ?? false;
            }

            Log::warning('Authorization request failed with status code: ' . $response->getStatusCode());
            return false;
        } catch (\Exception $e) {
            Log::error('Error during authorization request: ' . $e->getMessage());
            return false;
        }
    }
}
