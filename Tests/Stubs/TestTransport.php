<?php
namespace SimpleApiClientBundle\Tests\Stubs;

use SimpleApiClientBundle\Service\Transport\TransportInterface;

class TestTransport implements TransportInterface
{
    /** @var string */
    private $response;
    /** @var string */
    private $method;
    /** @var string */
    private $uri;

    /**
     * @param string $method
     * @param string $uri
     * @return string
     */
    public function request(string $method, string $uri) : string
    {
        $this->method = $method;
        $this->uri = $uri;

        return $this->response;
    }

    /**
     * @param string $response
     * @return self
     */
    public function setResponse(string $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }
}