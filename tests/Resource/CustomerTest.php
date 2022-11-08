<?php

namespace App\Tests\Resource;

use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
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
        ], 'auth_bearer' => $this->getPartnerToken()]);

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

    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     * @throws DecodingExceptionInterface
     */
    public function testRetrieveOnlyPartnerClients(){
        $this->client = static::createClient();
        $container = static::getContainer();

        $userRepository = $container->get(UserRepository::class);
        $customerRepository = $container->get(CustomerRepository::class);

        $partner = $userRepository->findOneBy([
            'email' => 'keira.beatty@hotmail.com'
        ]);

        $customers = $customerRepository->findBy([
            'partner' => $partner
        ]);

        $response = $this->client->request('GET', '/clients', ['auth_bearer' => $this->getPartnerToken()]);

        $json = $response->toArray();

        self::assertSame(count($json['hydra:member']), count($customers));

        foreach ($json['hydra:member'] as $item) {
            $c = $customerRepository->find($item['id']);
            self::assertSame($c->getPartner()->getId(), $partner->getId());
            self::assertArrayNotHasKey('created_at', $item);
        }
    }

    /**
     * @throws TransportExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws Exception
     */
    public function testRetrieveAllClientsWhenUserIsAdmin(){

        $container = static::getContainer();
        $clientRepository = $container->get(CustomerRepository::class);

        $response = $this->client->request('GET', '/clients', ['auth_bearer' => $this->getAdminToken()]);

        $json = $response->toArray();

        $customers = $clientRepository->findAll();
        self::assertSame(count($json['hydra:member']), count($customers));
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws Exception
     */
    public function testAdminHasAccessCreatedValue()
    {
        $container = static::getContainer();
        $customerRepository = $container->get(CustomerRepository::class);

        $customer = $customerRepository->findOneBy([
        ]);

        $response = $this->client->request(
            'GET',
            '/clients/'.$customer->getId(),
            ['auth_bearer' => $this->getAdminToken()]
        );

        $json = $response->toArray();
        self::assertArrayHasKey('created_at', $json);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws Exception
     */
    public function testPartnerHasNotAccessCreatedValue()
    {
        $container = static::getContainer();
        $userRepository = $container->get(UserRepository::class);
        $customerRepository = $container->get(CustomerRepository::class);

        $partner = $userRepository->findOneBy([
            'email' => 'keira.beatty@hotmail.com'
        ]);

        $customer = $customerRepository->findOneBy([
            'partner'=> $partner
        ]);

        $response = $this->client->request(
            'GET',
            '/clients/'.$customer->getId(),
            ['auth_bearer' => $this->getPartnerToken()]
        );

        $json = $response->toArray();
        self::assertArrayNotHasKey('created_at', $json);
    }
}