<?php

namespace App\Contracts;

use App\Request\ProcessPaymentRequest;

interface PaymentGatewayInterface
{
    public function charge(ProcessPaymentRequest $paymentRequest): PaymentProcessorInterface;
}