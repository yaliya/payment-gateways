<?php

namespace App\Controller;

use App\Factory\PaymentRequestFactory;
use App\Service\PaymentGatewayResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExampleController extends AbstractController
{
    public function __construct(
        private readonly PaymentGatewayResolver $resolver,
        private readonly PaymentRequestFactory $factory
    ) {}

    #[Route("/api/payments/{gateway}", name: "gateway", methods: "POST")]
    public function charge(string $gateway, Request $request) : Response
    {
        return $this->json(
            $this->resolver->gateway($gateway)->charge(
                $this->factory->create($request)
            )
        );
    }
}