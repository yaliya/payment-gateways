<?php

namespace App\Service\Gateways;

use App\Contracts\PaymentProcessorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractPaymentProcessor implements \JsonSerializable, PaymentProcessorInterface
{
    public string $id;
    public float $amount;
    public string $currency;
    public string $timestamp;
    public int $cardBin;
    public string $gateway;

    public function __construct(protected HttpClientInterface $client)
    {}

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'transactionId' => $this->id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'created' => $this->timestamp,
            'cardBin' => $this->cardBin,
            'gateway' => $this->gateway
        ];
    }
}