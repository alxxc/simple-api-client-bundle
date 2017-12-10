<?php
namespace SimpleApiClientBundle\Service\Transport;

interface TransportInterface
{
    public function request(string $method, string $uri) : string;
}