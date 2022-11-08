<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use DateTimeImmutable;
use Symfony\Component\Messenger\MessageBusInterface;

final class CustomerProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly ProcessorInterface $decorated,
        private readonly MessageBusInterface $bus
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $this->bus->dispatch($data);
        $data->setCreatedAt(new DateTimeImmutable());
        return $this->decorated->process($data, $operation, $uriVariables, $context);
    }
}
