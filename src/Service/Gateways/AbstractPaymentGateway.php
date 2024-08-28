<?php

namespace App\Service\Gateways;

use App\Contracts\PaymentProcessorInterface;
use App\Contracts\PaymentGatewayInterface;
use App\Request\ProcessPaymentRequest;

abstract class AbstractPaymentGateway implements PaymentGatewayInterface
{
    public function __construct(
        protected PaymentProcessorInterface $processor
    ) {}

    public function charge(ProcessPaymentRequest $paymentRequest): PaymentProcessorInterface
    {
        return $this->processor->authorize($paymentRequest)->capture();
    }
}