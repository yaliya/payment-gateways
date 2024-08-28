<?php

namespace App\Service\Gateways\Shift4;

use App\Request\ProcessPaymentRequest;
use App\Service\Gateways\AbstractPaymentProcessor;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

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
        $response = $this->client->request('POST', '/tokens', [
            'body' => [
                'number' => $paymentRequest->cardNumber,
                'expMonth' => $paymentRequest->expiryMonth,
                'expYear' => $paymentRequest->expiryYear,
                'cvc' => $paymentRequest->cvv,
                'cardholderName' => $paymentRequest->cardHolder
            ]
        ])->toArray();

        $this->id = $response['id'];
        $this->amount = $paymentRequest->amount;
        $this->currency = $paymentRequest->currency;
        $this->cardBin = $response['first6'];

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
        $response = $this->client->request('POST', '/charges', [
            'body' => [
                'amount' => $this->amount,
                'currency' => $this->currency,
                'customerId' => 'cust_sGPUdSaZWB1aLWPVMgacR6IP',
                'card' => $this->id
            ]
        ])->toArray();

        $this->id = $response['id'];
        $this->timestamp = $response['created'];
        $this->gateway = 'shift4';

        return $this;
    }
}