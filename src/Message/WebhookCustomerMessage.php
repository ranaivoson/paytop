<?php

namespace App\Message;

use App\Entity\Customer;

class WebhookCustomerMessage
{

    public function __construct(private Customer $customer)
    {
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }
}