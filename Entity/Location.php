<?php
namespace SimpleApiClientBundle\Entity;

class Location
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Coordinate
     */
    private $coordinates;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Location
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Coordinate
     */
    public function getCoordinates(): Coordinate
    {
        return $this->coordinates;
    }

    /**
     * @param Coordinate $coordinates
     *
     * @return Location
     */
    public function setCoordinates(Coordinate $coordinates)
    {
        $this->coordinates = $coordinates;

        return $this;
    }
}