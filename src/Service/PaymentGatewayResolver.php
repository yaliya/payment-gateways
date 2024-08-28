<?php

namespace App\Service;

use App\Contracts\PaymentGatewayInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class PaymentGatewayResolver
{
    /**
     * @param ServiceLocator<PaymentGatewayInterface> $locator
     */
    public function __construct(private ServiceLocator $locator) {}

    public function gateway(string $gateway): PaymentGatewayInterface
    {
        if (!$this->locator->has($gateway)) {
            throw new NotFoundHttpException(sprintf('Gateway "%s" not found.', $gateway));
        }

        return $this->locator->get($gateway);
    }
}