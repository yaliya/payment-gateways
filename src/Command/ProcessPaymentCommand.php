<?php

namespace App\Command;

use App\Request\ProcessPaymentRequest;
use App\Factory\PaymentRequestFactory;
use App\Service\PaymentGatewayResolver;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

#[AsCommand(
    name: 'app:process-payment',
    description: 'Payment Process Command',
    aliases: ['app:process-payment'],
    hidden: false
)]
class ProcessPaymentCommand extends Command
{
    public function __construct(
        protected PaymentGatewayResolver $resolver,
        protected PaymentRequestFactory $factory,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->resolver->gateway(
            $input->getArgument('gateway')
        )->charge(
            $this->factory->create($input)
        );

        $io = new SymfonyStyle($input, $output);

        $io->title('Payment Processed Successfully');

        $io->table(
            ['Transaction Id', 'Amount', 'Currency', 'Created Date', 'Card BIN', 'Gateway'],
            [$result->toArray()]
        );

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command processes a payment.')->addArgument('gateway', InputArgument::REQUIRED);

        $converter = new CamelCaseToSnakeCaseNameConverter();

        $class = new \ReflectionClass(ProcessPaymentRequest::class);

        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $this->addArgument($converter->normalize($reflectionProperty->getName()), InputArgument::REQUIRED);
        }
    }
}