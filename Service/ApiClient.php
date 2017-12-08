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
     * @param string $uri
     * @return Location[]
     * @throws BadResponseException
     * @throws ResponseMalformedException
     */
    public function loadLocations($uri)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client
            ->request('GET', $uri);

        echo $response->getStatusCode(); // 200

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
                return $jsonData->data->locations;
            } elseif (!empty($jsonData->code)) {
                // @todo process message
                throw new BadResponseException($jsonData->code);
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
}