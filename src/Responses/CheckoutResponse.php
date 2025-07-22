<?php

namespace Bohudur\LaravelSDK\Responses;

class CheckoutResponse
{
    private array $response = [];

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function status(): bool
    {
        return $this->success();
    }

    public function success(): bool
    {
        return strtolower($this->response['status'] ?? '') === 'success';
    }

    public function failed(): bool
    {
        return !$this->success();
    }
    
    public function responseCode(): ?int
    {
        return $this->response['responseCode'] ?? null;
    }

    public function message(): ?string
    {
        return $this->response['message'] ?? null;
    }
    
    public function paymentKey(): ?string
    {
        return $this->response['paymentkey'] ?? null;
    }

    public function paymentURL(): ?string
    {
        return $this->response['payment_url'] ?? null;
    }

    public function toArray(): array
    {
        return $this->response;
    }
}
