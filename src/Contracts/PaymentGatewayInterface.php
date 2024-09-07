<?php

namespace App\Contracts;

use App\Request\ProcessPaymentRequest;
use App\Request\RefundPaymentRequest;

interface PaymentGatewayInterface
{
    public function charge(ProcessPaymentRequest $paymentRequest): PaymentProcessorInterface;

    public function refund(RefundPaymentRequest $refundPaymentRequest): RefundProcessorInterface;
}