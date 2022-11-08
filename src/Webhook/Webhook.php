<?php

namespace App\Webhook;

use App\Entity\Customer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
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
        $normalizer = new ObjectNormalizer();
        $data = $normalizer->normalize($customer);
        $this->httpClient->request('POST', self::URL, [
            'json' => $data
        ]);
    }
}