<?php
namespace SimpleApiClientBundle\Service\Transport;

use GuzzleHttp\ClientInterface;

class GuzzleProxyClient
{
    private $guzzleClient;

    public function __construct(ClientInterface $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }


}