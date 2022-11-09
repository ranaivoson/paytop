<?php

namespace App\Message\Handler;

use App\Entity\Customer;
use App\Webhook\WebhookInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

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