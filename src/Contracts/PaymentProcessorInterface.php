<?php

namespace App\Contracts;

use App\Request\ProcessPaymentRequest;

interface PaymentProcessorInterface
{
    public function authorize(ProcessPaymentRequest $paymentRequest): PaymentProcessorInterface;

    public function capture(): PaymentProcessorInterface;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}