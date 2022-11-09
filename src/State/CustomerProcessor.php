<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Message\WebhookCustomerMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Security;

final class CustomerProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly ProcessorInterface $decorated,
        private readonly MessageBusInterface $bus,
        private readonly Security $security
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $this->bus->dispatch(new WebhookCustomerMessage(customer: $data));
        if ($user = $this->security->getUser()){
            $data->setPartner($user);
        }

        return $this->decorated->process($data, $operation, $uriVariables, $context);
    }
}
