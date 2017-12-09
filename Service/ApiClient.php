<?php
namespace SimpleApiClientBundle\Service;

use SimpleApiClientBundle\Entity\Location;
use SimpleApiClientBundle\Exception\BadResponseException;
use SimpleApiClientBundle\Exception\ResponseMalformedException;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Serializer;

class ApiClient
{
    /**
     * @var string|null
     */
    private $baseUri = null;

    /**
     * @var int
     */
    private $timeout = 0;

    /**
     * @var bool
     */
    private $allow_redirects = false;

    /**
     * @var string|null
     */
    private $proxy = null;

    public function __construct($timeout = 0)
    {
        $this->timeout = $timeout;
    }

    /**
     * Load locations info from remote uri
     *
     * @param string $uri
     * @return Location[]
     * @throws BadResponseException
     * @throws ResponseMalformedException
     */
    public function loadLocations($uri)
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => $this->baseUri,
            'timeout' => $this->timeout,
            'allow_redirects' => $this->allow_redirects,
            'proxy' => $this->proxy,
        ]);

        $response = $client
            ->request('GET', $uri);

        // @todo process status code? $response->getStatusCode(); // 200

        $body = $response->getBody();

        $data = $this->processResponse($body);

        return $this->denormalizeLocations($data);
    }

    /**
     * @param $response
     * @return mixed
     * @throws BadResponseException
     * @throws ResponseMalformedException
     */
    protected function processResponse($response)
    {
        $jsonData = \GuzzleHttp\json_decode($response);

        if (isset($jsonData->success)) {
            if ($jsonData->success) {
                if (!empty($jsonData->data) && !empty($jsonData->data->locations)) {
                    return $jsonData->data->locations;
                } else {
                    throw new ResponseMalformedException('Bad data format');
                }
            } elseif (!empty($jsonData->message)) {
                throw new BadResponseException($jsonData->message);
            } else {
                throw new ResponseMalformedException('Error code is empty');
            }
        } else {
            throw new ResponseMalformedException('Field "success" is not defined');
        }
    }

    /**
     * @param array $data
     * @return Location[]
     */
    protected function denormalizeLocations($data)
    {
        $normalizer = new GetSetMethodNormalizer(null, null, new ReflectionExtractor());
        $serializer = new Serializer([
            $normalizer,
            new ArrayDenormalizer(),
        ]);

        return $serializer->denormalize($data, Location::class.'[]');
    }


    /**
     * @return null
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * @param null $baseUri
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
     * @return null
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * @param null $proxy
     * @return self
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
        return $this;
    }
}