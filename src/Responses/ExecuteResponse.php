<?php

namespace Bohudur\LaravelSDK\Responses;

class ExecuteResponse
{
    private array $response = [];

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function status(): ?string
    {
        if(isset($this->response['Status'])){
            return $this->response['Status'];
        }else if(isset($this->response['status'])){
            return $this->response['status'];
        }else{
            return null;
        }
    }

    public function success(): bool
    {
        return $this->status() === 'EXECUTED';
    }
    
    public function pending(): bool
    {
        return $this->responseCode() === 808;
    }

    public function failed(): bool
    {
        return $this->status() === 'failed';
    }

    public function fullName(): ?string
    {
        return $this->response['Full Name'] ?? null;
    }

    public function email(): ?string
    {
        return $this->response['Email'] ?? null;
    }

    public function amount(): ?float
    {
        return isset($this->response['Amount']) ? (float)$this->response['Amount'] : null;
    }

    public function convertedAmount(): ?float
    {
        return isset($this->response['Converted Amount']) ? (float)$this->response['Converted Amount'] : null;
    }

    public function totalAmount(): ?float
    {
        return isset($this->response['Total Amount']) ? (float)$this->response['Total Amount'] : null;
    }
    
    public function currency(): ?string
    {
        return $this->response['Currency'] ?? null;
    }

    public function currencyValue(): ?float
    {
        return isset($this->response['Currency Value']) ? (float)$this->response['Currency Value'] : null;
    }

    public function paymentKey(): ?string
    {
        return $this->response['Payment Key'] ?? null;
    }

    public function metadata(): array
    {
        $data = $this->response['Metadata'] ?? '[]';
        return is_string($data) ? json_decode($data, true) ?? [] : (array)$data;
    }

    public function webhook(): array
    {
        $data = $this->response['Webhook'] ?? '[]';
        return is_string($data) ? json_decode($data, true) ?? [] : (array)$data;
    }

    public function paymentMethod(): ?string
    {
        return $this->response['MFS'] ?? null;
    }
    
    public function method(): ?string
    {
        return $this->response['Method'] ?? null;
    }

    public function senderNumber(): ?string
    {
        return $this->response['Number'] ?? null;
    }

    public function transactionId(): ?string
    {
        return $this->response['Transaction ID'] ?? null;
    }
    
    public function paymentTime(): ?string
    {
        return $this->response['Payment Time'] ?? null;
    }
    
    public function time(): ?string
    {
        return $this->response['Time'] ?? null;
    }
    
    public function responseCode(): ?int
    {
        return isset($this->response['responseCode']) ? (int)$this->response['responseCode'] : null;
    }
    
    public function message(): ?string
    {
        return $this->response['message'] ?? null;
    }

    public function toArray(): array
    {
        return $this->response;
    }
}
