<?php
namespace SimpleApiClientBundle\Service;

use SimpleApiClientBundle\Entity\Location;
use SimpleApiClientBundle\Exception\BadResponseException;
use SimpleApiClientBundle\Exception\ResponseMalformedException;
use SimpleApiClientBundle\Service\Transport\TransportInterface;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Serializer;

class ApiClient
{
    private $transport;

    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    /**
     * Load locations info from remote uri
     *
     * @param string $uri
     * @return Location[]
     * @throws BadResponseException
     * @throws ResponseMalformedException
     */
    public function loadLocations(string $uri)
    {
        $response = $this->transport
            ->request('GET', $uri);

        $data = $this->processResponse($response);

        return $this->denormalizeLocations($data);
    }

    /**
     * @param $response
     * @return mixed
     * @throws BadResponseException
     * @throws ResponseMalformedException
     */
    protected function processResponse(string $response)
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
                throw new ResponseMalformedException('Error message is empty');
            }
        } else {
            throw new ResponseMalformedException('Field "success" is not defined');
        }
    }

    /**
     * @param array $data
     * @return Location[]
     */
    protected function denormalizeLocations(array $data)
    {
        $normalizer = new GetSetMethodNormalizer(null, null, new ReflectionExtractor());
        $serializer = new Serializer([
            $normalizer,
            new ArrayDenormalizer(),
        ]);

        return $serializer->denormalize($data, Location::class.'[]');
    }
}