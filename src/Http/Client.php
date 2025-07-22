<?php

namespace Bohudur\LaravelSDK\Http;

use Illuminate\Http\Client\PendingRequest;

use Illuminate\Support\Facades\Http;
use Bohudur\LaravelSDK\Exceptions\BohudurException;
use Bohudur\LaravelSDK\Requests\CheckoutRequest;
use Bohudur\LaravelSDK\Responses\CheckoutResponse;
use Bohudur\LaravelSDK\Responses\VerifyResponse;
use Bohudur\LaravelSDK\Responses\ExecuteResponse;

class Client
{

    private PendingRequest $client;

    private string $apiKey;

    private string $apiURL;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->apiURL = 'https://request.bohudur.one';

        $this->client = Http::baseUrl($this->apiURL)
            ->withHeaders([
                'AH-BOHUDUR-API-KEY' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->timeout(30)
            ->acceptJson();
    }
    
    public function createPayment(CheckoutRequest $request): CheckoutResponse
    {
        try {
            $response = $this->client->post('/create/', $request->build());

            if (! $response->successful()) {
                throw new BohudurException('Bohudur API Error: '.$response->body());
            }

            return new CheckoutResponse($response->json());
        } catch (\Exception $e) {
            throw new BohudurException('Bohudur Error: '.$e->getMessage());
        }
    }
    

    public function executePayment(string $paymentkey): VerifyResponse
    {
        try {
            if (strlen($paymentkey) != 20) {
                throw new BohudurException('Payment Key is wrong.');
            }

            $response = $this->client->post('/execute/', [
                'paymentkey' => $paymentkey,
            ]);

            if (! $response->successful()) {
                throw new BohudurException('Bohudur Execution Error: '.$response->body());
            }

            return new VerifyResponse($response->json());
        } catch (\Exception $e) {
            throw new BohudurException('Bohudur Execution Error: '.$e->getMessage());
        }
    }
    

    public function verifyPayment(string $paymentkey): VerifyResponse
    {
        try {
            if (strlen($paymentkey) != 20) {
                throw new BohudurException('Payment Key is wrong.');
            }

            $response = $this->client->post('/verify/', [
                'paymentkey' => $paymentkey,
            ]);

            if (! $response->successful()) {
                throw new BohudurException('Bohudur Verification Error: '.$response->body());
            }

            return new VerifyResponse($response->json());
        } catch (\Exception $e) {
            throw new BohudurException('Bohudur Verification Error: '.$e->getMessage());
        }
    }
}
