<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class ProcessPaymentRequest extends AbstractRequest
{
    #[Type('float')]
    #[NotBlank()]
    public float $amount;

    #[Type('string')]
    #[NotBlank([])]
    public string $currency;

    #[Type('integer')]
    #[NotBlank([])]
    public int $cardNumber;

    #[Type('string')]
    #[NotBlank()]
    public string $cardHolder;

    #[Type('integer')]
    #[NotBlank([])]
    public int $expiryYear;

    #[Type('integer')]
    #[NotBlank([])]
    public int $expiryMonth;

    #[Type('integer')]
    #[NotBlank([])]
    public int $cvv;
}