<?php

namespace App\Tests\Security;

use App\Tests\AbstractTest;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AuthenticationTest extends AbstractTest
{
    use RefreshDatabaseTrait;


    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testLogin(): void
    {
        $client = static::createClient();

        $response = $client->request('POST', '/authentication_token', ['json' => [
            'username' => 'admin@example.com',
            'password' => 'password',
        ]]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

        // test not authorized
        $client->request('GET', '/clients');
        $this->assertResponseStatusCodeSame(401);

        // test authorized
        $client->request('GET', '/clients', ['auth_bearer' => $json['token']]);
        $this->assertResponseIsSuccessful();
    }
}