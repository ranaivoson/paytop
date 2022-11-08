<?php

namespace App\Tests\Resource;

use App\Tests\AbstractTest;
use Exception;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CustomerTest extends AbstractTest
{
    use RefreshDatabaseTrait;

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testCreateCustomer(): void
    {
        $this->client->request('POST', '/clients', ['json' => [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@test.fr',
            'phone_number' => '0761109876',
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/Client',
            '@type' => 'Client',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@test.fr',
            'phone_number' => '0761109876'
        ]);
    }

//    /**
//     * @throws TransportExceptionInterface
//     * @throws Exception
//     */
//    public function testWebhook(){
//        $this->client->request('POST', '/clients', ['json' => [
//            'first_name' => 'John',
//            'last_name' => 'Doe',
//            'email' => 'test@test.fr',
//            'phone_number' => '0761109876',
//        ]]);
//
//        $transport = $this->getContainer()->get('messenger.transport.async_priority_normal');
//        $this->assertCount(1, $transport->getSent());
//    }
}