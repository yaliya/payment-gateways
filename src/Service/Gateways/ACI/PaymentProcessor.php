<?php

namespace App\Service\Gateways\ACI;

use App\Request\ProcessPaymentRequest;
use App\Service\Gateways\AbstractPaymentProcessor;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

final class PaymentProcessor extends AbstractPaymentProcessor
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function authorize(ProcessPaymentRequest $paymentRequest): PaymentProcessor
    {
        $response = $this->client->request('POST', '/v1/payments', [
            'body' => [
                'entityId' => '8a8294174b7ecb28014b9699220015ca',
                'amount' => $paymentRequest->amount,
                'currency' => $paymentRequest->currency,
                'paymentBrand' => 'VISA',
                'paymentType' => 'PA',
                'card.number' => $paymentRequest->cardNumber,
                'card.holder' => $paymentRequest->cardHolder,
                'card.expiryMonth' => $paymentRequest->expiryMonth,
                'card.expiryYear' => $paymentRequest->expiryYear,
                'card.cvv' => $paymentRequest->cvv
            ]
        ])->toArray();

        $this->id = $response['id'];
        $this->amount = floatval($response['amount']);
        $this->currency = $response['currency'];
        $this->cardBin = intval($response['card']['bin']);

        return $this;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function capture(): PaymentProcessor
    {
        $response = $this->client->request('POST', '/v1/payments/' . $this->id, [
            'body' => [
                'entityId' => '8a8294174b7ecb28014b9699220015ca',
                'amount' => $this->amount,
                'currency' => $this->currency,
                'paymentType' => 'CP'
            ]
        ])->toArray();

        $this->id = $response['id'];
        $this->timestamp = $response['timestamp'];
        $this->gateway = 'aci';

        return $this;
    }
}