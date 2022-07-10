<?php

namespace App\Http\Traits;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;


trait AntMedia
{
    public $client;
    public $jwt;
    public $headers;

    /**
     * @return string
     */
    public function generateToken(): string
    {
        $secret = env('AND_MEDIA_API_SECRET', '');
        $payload = [
            'exp' => strtotime('+1 minute'),
        ];

        return JWT::encode($payload, $secret, 'HS256');
    }

    /**
     * @return mixed
     */
    private function retrieveUrl()
    {
        return env('AND_MEDIA_API_URL', '');
    }

    /**
     * @param $broadcastId
     * @return array
     * @throws GuzzleException
     */
    public function getBroadcast($broadcastId): array
    {
        $this->client = new Client([
            'base_uri' => env('AND_MEDIA_API_URL', '')
        ]);

        $this->jwt = $this->generateToken();

        $this->headers = [
            'Authorization' => $this->jwt,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
        $path = 'broadcasts/' . $broadcastId;
        $url = $this->retrieveUrl();

        $options = [
            'headers' => $this->headers,
        ];

        $response = $this->client->get($url . $path, $options);

        return [
            'data' => json_decode($response->getBody(), true),
        ];
    }


    /**
     * @param $data
     * @return array
     * @throws GuzzleException
     */
    public function createBroadcast($data): array
    {
        $this->client = new Client([
            'base_uri' => env('AND_MEDIA_API_URL', '')
        ]);

        $this->jwt = $this->generateToken();

        $this->headers = [
            'Authorization' => $this->jwt,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
        $path = 'broadcasts/create';
        $url = $this->retrieveUrl();

        $options = [
            'headers' => $this->headers,
            'body' => json_encode([
                'name' => $data->name,
                'description' => $data->description,
            ]),
        ];

        $response = $this->client->post($url . $path, $options);

        return [
            'data' => json_decode($response->getBody(), true),
        ];
    }

    /**
     * @param $streamId
     * @param $data
     * @return array
     * @throws GuzzleException
     */
    public function updateBroadcast($streamId, $data): array
    {
        $this->client = new Client([
            'base_uri' => env('AND_MEDIA_API_URL', '')
        ]);

        $this->jwt = $this->generateToken();

        $this->headers = [
            'Authorization' => $this->jwt,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
        $path = 'broadcasts/' . $streamId;
        $url = $this->retrieveUrl();

        $options = [
            'headers' => $this->headers,
            'body' => json_encode([
                'name' => $data->name,
                'description' => $data->description,
            ]),
        ];

        $response = $this->client->put($url . $path, $options);

        return [
            'data' => json_decode($response->getBody(), true),
        ];
    }

    /**
     * @param $streamId
     * @return bool[]
     * @throws GuzzleException
     */
    public function deleteBroadcast($streamId): array
    {
        $this->client = new Client([
            'base_uri' => env('AND_MEDIA_API_URL', '')
        ]);

        $this->jwt = $this->generateToken();

        $this->headers = [
            'Authorization' => $this->jwt,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
        $path = 'broadcasts/' . $streamId;
        $url = $this->retrieveUrl();

        $options = [
            'headers' => $this->headers,
        ];

        $response = $this->client->delete($url . $path, $options);

        return [
            'data' => json_decode($response->getBody(), true),
        ];
    }

}
