<?php
namespace SimpleApiClientBundle\Service\Transport;

use GuzzleHttp\Client;

class TransportFactory
{
    public function __construct($timeout)
    {

    }

    public function getClient()
    {
        return new Client([
            'base_uri' => 'http://www.foo.com/1.0/',
            'timeout' => 0,
            'allow_redirects' => false,
            'proxy' => '192.168.16.1:10',
        ]);
    }
}