<?php

namespace App\Service\Gateways\Shift4;

use App\Contracts\PaymentGatewayInterface;
use App\Contracts\PaymentProcessorInterface;
use App\Contracts\RefundProcessorInterface;
use App\Request\ProcessPaymentRequest;
use App\Request\RefundPaymentRequest;
use App\Service\Gateways\AbstractPaymentGateway;

final class PaymentGateway extends AbstractPaymentGateway implements PaymentGatewayInterface
{
    public function charge(ProcessPaymentRequest $paymentRequest): PaymentProcessorInterface
    {
        return $this->processor->authorize($paymentRequest)->capture();
    }

    public function refund(RefundPaymentRequest $refundPaymentRequest): RefundProcessorInterface
    {
        // TODO: Implement refund() method.
    }
}