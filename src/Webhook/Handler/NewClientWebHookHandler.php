<?php

namespace App\Webhook\Handler;

use App\Entity\Customer;
use App\Webhook\WebhookInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class NewClientWebHookHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly WebhookInterface    $webhook,
    ){

    }

    /**
     */
    #[NoReturn]
    public function __invoke(Customer $customer)
    {
        $this->webhook->send($customer);
    }
}