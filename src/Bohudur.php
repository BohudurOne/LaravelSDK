<?php

namespace Bohudur\LaravelSDK;

use Illuminate\Http\Request;
use Bohudur\LaravelSDK\Http\Client;
use Bohudur\LaravelSDK\Requests\CheckoutRequest;
use Bohudur\LaravelSDK\Responses\CheckoutResponse;
use Bohudur\LaravelSDK\Responses\VerifyResponse;
use Bohudur\LaravelSDK\Responses\ExecuteResponse;

class Bohudur
{
    private Client $client;

    public static function make(string $apiKey): self
    {
        $instance = new self;
        $instance->client = new Client($apiKey);

        return $instance;
    }

    public function checkout(CheckoutRequest $request): CheckoutResponse
    {
        return $this->client->createPayment($request);
    }

    public function verify(string $paymentkey): VerifyResponse
    {
        return $this->client->verifyPayment($paymentkey);
    }

    public function execute(string $paymentkey): ExecuteResponse
    {
        return $this->client->executePayment($paymentkey);
    }
}
