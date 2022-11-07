<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;

class AbstractTest extends ApiTestCase
{
    use RecreateDatabaseTrait;

    protected Client $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }
}