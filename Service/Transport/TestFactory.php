<?php
namespace SimpleApiClientBundle\Service\Transport;

use GuzzleHttp\ClientInterface;

class TestFactory implements TransportFactoryInterface
{
    private $client;

    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getClient(): ClientInterface
    {
        return $this->client;
    }
}