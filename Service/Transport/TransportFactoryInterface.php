<?php
namespace SimpleApiClientBundle\Service\Transport;

use GuzzleHttp\ClientInterface;

interface TransportFactoryInterface
{
    public function getClient() : ClientInterface;
}