<?php

namespace App\Webhook;

use App\Entity\Customer;

interface WebhookInterface
{
    public function send(Customer $customer);
}