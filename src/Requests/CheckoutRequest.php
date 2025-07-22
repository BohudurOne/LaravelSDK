<?php

namespace Bohudur\LaravelSDK\Requests;

use Bohudur\LaravelSDK\Exceptions\BohudurException;

class CheckoutRequest
{
    private array $data = [
        'return_type' => 'POST',
    ];
    
    private array $metadata = [];
    
    public function setFullName(string $fullName): self
    {
        $this->data['fullname'] = $fullName;
        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->data['email'] = $email;
        return $this;
    }

    public function setAmount(float|int $amount): self
    {
        $this->data['amount'] = $amount;
        return $this;
    }

    public function setCurrency(string $code): self
    {
        $this->data['currency'] = strtoupper($code);
        return $this;
    }

    public function setCurrencyValue(float|int $value): self
    {
        $this->data['currency_value'] = $value;
        return $this;
    }
    
    public function setRedirectUrl(string $url): self
    {
        $this->data['redirect_url'] = $url;
        return $this;
    }

    public function setCancelUrl(string $url): self
    {
        $this->data['cancelled_url'] = $url;
        return $this;
    }

    public function setWebhookSuccessUrl(string $url): self
    {
        $this->data['webhook']['success'] = $url;
        return $this;
    }

    public function setWebhookCancelUrl(string $url): self
    {
        $this->data['webhook']['cancel'] = $url;
        return $this;
    }
    
    public function setReturnType(string $type = 'POST'): self
    {
        $type = strtoupper($type);
        if ($type !== 'POST' && $type !== 'GET') {
            throw new BohudurException('return_type must be GET or POST');
        }
        $this->data['return_type'] = $type;
        return $this;
    }
    
    public function addMetadata(string $key, mixed $value): self
    {
        $this->metadata[$key] = $value;
        return $this;
    }

    public function setMetadata(array $all): self
    {
        $this->metadata = $all;
        return $this;
    }
    
    public function build(): array
    {
        // attach metadata if present
        if ($this->metadata !== []) {
            $this->data['metadata'] = $this->metadata;
        }

        // validate required keys
        $required = [
            'fullname', 'email', 'amount',
            'redirect_url', 'cancelled_url',
            'return_type',          // webhook holds success+cancel
        ];

        foreach ($required as $key) {
            if (!array_key_exists($key, $this->data)) {
                throw new BohudurException("Missing required field: {$key}");
            }
        }

        return $this->data;
    }
    
    public static function make(): self
    {
        return new self();
    }
}
