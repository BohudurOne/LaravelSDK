<?php
namespace App\Modules\Bohudur\Services;

use App\Modules\Bohudur\Exceptions\BohudurException;
use RuntimeException;

class BohudurRequest {
    private string $apiKey;
    private string $baseUrl;
    private array $payload = [];

    public function __construct(string $apiKey, string $baseUrl) {
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
    }

    public function fullName(string $name): self { $this->payload['full_name'] = $name; return $this; }
    public function email(string $email): self { $this->payload['email'] = $email; return $this; }
    public function amount(float $amount): self { $this->payload['amount'] = $amount; return $this; }
    public function returnType(string $type): self { $this->payload['return_type'] = $type; return $this; }
    public function redirectUrl(string $url): self { $this->payload['redirect_url'] = $url; return $this; }
    public function cancelUrl(string $url): self { $this->payload['cancel_url'] = $url; return $this; }
    public function metadata(array $data): self { $this->payload['metadata'] = $data; return $this; }
    public function webhook(array $urls): self { $this->payload['webhook'] = $urls; return $this; }

    public function send() {
        $url = $this->baseUrl . 'create/v2/';
        $headers = [
            'Content-Type: application/json',
            'AH-BOHUDUR-API-KEY: ' . $this->apiKey
        ];
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($this->payload),
            CURLOPT_TIMEOUT => 30
        ]);
        $response = curl_exec($ch);
        if ($response === false) {
            throw new BohudurException('cURL Error: ' . curl_error($ch));
        }
        $json = json_decode($response, false);
        return json_last_error() === JSON_ERROR_NONE ? $json : $response;
    }
}