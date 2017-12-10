<?php

namespace SimpleApiClientBundle\Tests\Controller;

use SimpleApiClientBundle\Entity\Location;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ApiClientIntegrationTest extends KernelTestCase
{
    private $apiClient;

    protected function setUp()
    {
        $kernel = static::bootKernel();
        $this->apiClient = $kernel->getContainer()->get('simple_api_client.client');
    }

    public function testOneEntity()
    {
        $locations = $this->apiClient->loadLocations('http://localhost:8000/s1.json');

        $this->assertCount(1, $locations);

        $this->assertLocation(reset($locations), 'Eiffel Tower', 21.12, 19.56);
    }

    public function testSomeEntities()
    {
        $locations = $this->apiClient->loadLocations('http://localhost:8000/s2.json');

        $this->assertCount(2, $locations);

        $this->assertLocation(reset($locations), 'Eiffel Tower', 21.12, 19.56);
        $this->assertLocation(next($locations), 'The Statue of Liberty', 40.69, 74.03);
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
        $this->apiClient->loadLocations('http://localhost:8000/f1.json');
    }

    /**
     * @expectedException \SimpleApiClientBundle\Exception\ResponseMalformedException
     */
    public function testBadFormattedError()
    {
        $this->apiClient->loadLocations('http://localhost:8000/f2.json');
    }
}
