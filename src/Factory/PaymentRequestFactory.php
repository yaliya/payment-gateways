<?php

namespace App\Factory;

use App\Request\ProcessPaymentRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

final readonly class PaymentRequestFactory
{
    public function __construct(private ProcessPaymentRequest $request)
    {}

    private function createFromRequest(Request $input): void
    {
        $data = $input->getPayload()->all();

        foreach ($data as $property => $value) {
            if (property_exists($this->request, $property)) {
                $this->request->{$property} = $value;
            }
        }
    }

    /**
     * Creates a request object from input interface
     *
     * @param InputInterface $input
     * @return void
     */
    private function createFromCli(InputInterface $input): void
    {
        $class = new \ReflectionClass($this->request);
        $converter = new CamelCaseToSnakeCaseNameConverter();

        if ($class->getConstructor()) {
            foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
                $this->request->{$property->getName()} = $input->getArgument($converter->normalize($property->getName()));
            }
        }
    }

    public function create(Request|InputInterface $input): ProcessPaymentRequest
    {
        $input instanceof Request ? $this->createFromRequest($input) : $this->createFromCli($input);

        $this->request->validate();

        return $this->request;
    }
}