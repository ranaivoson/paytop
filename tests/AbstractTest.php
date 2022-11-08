<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use Exception;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AbstractTest extends ApiTestCase
{
    use RecreateDatabaseTrait;

    protected Client $client;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->client = static::createClient();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function getAdminToken(){
        return $this->getToken();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function getPartnerToken(){
        $body = [
            'username' => 'keira.beatty@hotmail.com',
            'password' => 'password'
        ];
        return $this->getToken($body);
    }



    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function getToken($body = []): string
    {
        $response = $this->client->request('POST', '/authentication_token', ['json' => $body ?: [
            'username' => 'admin@example.com',
            'password' => 'password',
        ]]);

        $this->assertResponseIsSuccessful();
        $json = $response->toArray();

        return $json['token'];
    }
}