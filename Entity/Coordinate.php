<?php
namespace SimpleApiClientBundle\Entity;

class Coordinate
{
    /**
     * latitude
     * @var float
     */
    private $lat;

    /**
     * longitude
     * @var float
     */
    private $long;

    /**
     * @return float
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     *
     * @return Coordinate
     */
    public function setLat(float $lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * @return float
     */
    public function getLong(): float
    {
        return $this->long;
    }

    /**
     * @param float $long
     *
     * @return Coordinate
     */
    public function setLong(float $long)
    {
        $this->long = $long;

        return $this;
    }
}