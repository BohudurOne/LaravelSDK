<?php
namespace App\Modules\Bohudur\Services;

use App\Modules\Bohudur\Exceptions\BohudurException;
use RuntimeException;
use InvalidArgumentException;

class BohudurService {
    private string $apiKey;
    private string $baseUrl;

    public function __construct() {
        $this->apiKey = config('bohudur.api_key');
        $this->baseUrl = config('bohudur.base_url');
        if (!$this->apiKey) {
            throw new BohudurException('Bohudur API key not set in config or .env file.');
        }
    }

    public function request(): BohudurRequest {
        return new BohudurRequest($this->apiKey, $this->baseUrl);
    }

    public function query(string $paymentKey) {
        if (empty($paymentKey)) {
            throw new InvalidArgumentException('Payment key is required.');
        }
        return $this->send('query/v2/', 'POST', ['paymentkey' => $paymentKey]);
    }

    public function execute(string $paymentKey) {
        if (empty($paymentKey)) {
            throw new InvalidArgumentException('Payment key is required.');
        }
        return $this->send('execute/v2/', 'POST', ['paymentkey' => $paymentKey]);
    }

    private function send(string $endpoint, string $method='POST', array $payload=[]) {
        $url = $this->baseUrl . ltrim($endpoint, '/');
        $headers = [
            'Content-Type: application/json',
            'AH-BOHUDUR-API-KEY: ' . $this->apiKey
        ];
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_TIMEOUT => 30
        ]);
        if ($method !== 'GET' && !empty($payload)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        }
        $response = curl_exec($ch);
        if ($response === false) {
            throw new BohudurException('cURL Error: ' . curl_error($ch));
        }
        $json = json_decode($response, false);
        return json_last_error() === JSON_ERROR_NONE ? $json : $response;
    }
}