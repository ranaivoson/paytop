<?php

namespace App\Webhook;

use App\Entity\Customer;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Webhook implements WebhookInterface
{
    const URL = 'https://webhook.site/299381de-8c70-4a56-b2c9-156436ee62d4';

    public function __construct(private readonly HttpClientInterface $httpClient)
    {
    }

    /**
     * @throws TransportExceptionInterface|ExceptionInterface
     */
    public function send(Customer $customer)
    {
        $normalizer = new ObjectNormalizer(null, null, null, new ReflectionExtractor());
        $serializer = new Serializer([new DateTimeNormalizer(), $normalizer]);
        $data = $serializer->normalize($customer, null, ['groups' => 'read']);
        $this->httpClient->request('POST', self::URL, [
            'json' => $data
        ]);
    }
}