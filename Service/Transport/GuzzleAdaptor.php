<?php
namespace SimpleApiClientBundle\Service\Transport;

use GuzzleHttp\Client;

class GuzzleAdaptor implements TransportInterface
{
    /** @var string|null */
    private $baseUri = null;

    /** @var int */
    private $timeout = 20;

    /** @var bool */
    private $allow_redirects = false;

    /** @var string|null */
    private $proxy = null;

    private $client;

    /**
     * @param string $method
     * @param string $uri
     * @return string
     */
    public function request(string $method, string $uri) : string
    {
        $response = $this->getClient()->request($method, $uri);

        return $response->getBody();
    }

    /**
     * @return Client
     */
    private function getClient() : Client
    {
        if (!$this->client) {
            $this->client = new Client([
                'timeout' => $this->timeout,
                'base_uri' => $this->baseUri,
                'allow_redirects' => $this->allow_redirects,
                'proxy' => $this->proxy,
            ]);
        }

        return $this->client;
    }

    /**
     * @return string|null
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * @param string|null $baseUri
     * @return self
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     * @return self
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowRedirects()
    {
        return $this->allow_redirects;
    }

    /**
     * @param bool $allow_redirects
     * @return self
     */
    public function setAllowRedirects($allow_redirects)
    {
        $this->allow_redirects = $allow_redirects;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * @param string|null $proxy
     * @return self
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
        return $this;
    }
}