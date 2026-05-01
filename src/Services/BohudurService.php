<?php
namespace App\Modules\Bohudur\Services;

use App\Modules\Bohudur\Exceptions\BohudurException;
use InvalidArgumentException;

class BohudurService {
    /**
     * Runtime API key set via Bohudur::init('api_key').
     * Takes priority over .env / config when present.
     */
    private static ?string $runtimeApiKey = null;

    private string $apiKey;
    private string $baseUrl;

    /**
     * Set the API key at runtime.
     * Call this before Bohudur::request() when you need a dynamic key.
     *
     * Example:
     *   Bohudur::init('your-api-key');
     *   $response = Bohudur::request()->amount(10)->send();
     */
    public static function init(string $apiKey): void {
        if (empty(trim($apiKey))) {
            throw new BohudurException('Bohudur API key provided to init() cannot be empty.');
        }
        self::$runtimeApiKey = $apiKey;
    }

    public function __construct() {
        // Priority: runtime key (from init()) → config/env
        $this->apiKey = self::$runtimeApiKey ?? config('bohudur.api_key');
        $this->baseUrl = config('bohudur.base_url');

        if (empty($this->apiKey)) {
            throw new BohudurException('Bohudur API key not set. Use Bohudur::init("api_key") or set BOHUDUR_API_KEY in your .env file.');
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

    private function send(string $endpoint, string $method = 'POST', array $payload = []) {
        $url = $this->baseUrl . ltrim($endpoint, '/');
        $headers = [
            'Content-Type: application/json',
            'AH-BOHUDUR-API-KEY: ' . $this->apiKey
        ];
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_TIMEOUT        => 30
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
