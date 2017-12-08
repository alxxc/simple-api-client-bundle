<?php

namespace SimpleApiClientBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ApiClientIntegrationTest extends KernelTestCase
{
    public function testIndex()
    {
        $kernel = static::bootKernel();
        $apiClient = $kernel->getContainer()->get('simple_api_client.client');
        $locations = $apiClient->loadLocations('http://127.0.0.1:8000/testapi/s1.json');

        $this->assertCount(1, $locations);

        $location = reset($locations);
        $this->assertEquals($location->getName(), 'Eiffel Tower');

        $coordinates = $location->getCoordinates();
        $this->assertEquals($coordinates->getLong(), 19.56);
        $this->assertEquals($coordinates->getLat(), 21.12);
    }
}
