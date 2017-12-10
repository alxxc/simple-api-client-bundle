<?php
namespace SimpleApiClientBundle\Tests\Controller;

use PHPUnit\Framework\TestCase;
use SimpleApiClientBundle\Entity\Location;
use SimpleApiClientBundle\Service\ApiClient;
use SimpleApiClientBundle\Tests\Stubs\TestTransport;

class ApiClientTest extends TestCase
{
    public function testSuccessResponse()
    {
        $transport = (new TestTransport())->setResponse(
        <<<JSON
{
  "data": {
    "locations": [
      {
        "name": "Eiffel Tower",
        "coordinates": {
          "lat": 21.12,
          "long": 19.56
        }
      }
    ]
  },
  "success": true
}
JSON
    );

        $client = new ApiClient($transport);

        $url = 'http://test.host/s1.json';
        $locations = $client->loadLocations($url);

        $this->assertCount(1, $locations);

        $this->assertLocation(reset($locations), 'Eiffel Tower', 21.12, 19.56);

        $this->assertEquals($url, $transport->getUri());
    }

    private function assertLocation(Location $location, string $nameExpected, string $latExpected, string $longExpected)
    {
        $this->assertEquals($location->getName(), $nameExpected);

        $coordinates = $location->getCoordinates();
        $this->assertEquals($coordinates->getLong(), $longExpected);
        $this->assertEquals($coordinates->getLat(), $latExpected);
    }

    /**
     * @expectedException \SimpleApiClientBundle\Exception\ResponseMalformedException
     */
    public function testGoodFormattedError()
    {
        $transport = (new TestTransport())->setResponse(
            <<<JSON
{
  "data": {
    "message": "string error message",
    "code": "string error code"
  },
  "success": false
}
JSON
        );

        (new ApiClient($transport))->loadLocations('http://test.host/f1.json');
    }

    /**
     * @expectedException \SimpleApiClientBundle\Exception\ResponseMalformedException
     */
    public function testBadFormattedError()
    {
        $transport = (new TestTransport())->setResponse(
            <<<JSON
{
  "success": true
}
JSON
        );

        (new ApiClient($transport))->loadLocations('http://test.host/f2.json');
    }
}