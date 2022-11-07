<?php

namespace App\Tests\Resource;

use App\Tests\AbstractTest;

class CustomerTest extends AbstractTest
{
    public function testCreateCustomer(): void
    {
        $this->client->request('POST', '/customers', ['json' => [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@test.fr',
            'phone_number' => '0761109876',
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/Customer',
            '@type' => 'Customer',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@test.fr',
            'phone_number' => '0761109876'
        ]);
    }
}