<?php

namespace phpOMS\Math\Optimization\TSP;

use phpOMS\Math\Shape\D3\Sphere;

class City
{
    private $name = '';
    private $long = 0.0;
    private $lat  = 0.0;

    public function __construct(\float $lat, \float $long, \string $name)
    {
        $this->long = $long;
        $this->lat  = $lat;
        $this->name = $name;
    }

    public function getLongitude() : \float
    {
        return $this->long;
    }

    public function getLatitude() : \float
    {
        return $this->lat;
    }

    public function getName() : \string
    {
        return $this->name;
    }

    public function equals(City $city) : \bool
    {
        return $this->name === $city->getName() && $this->lat === $city->getLatitude() && $this->long === $city->getLatitude();
    }

    public function getDistanceTo(City $city) : \float
    {
        return Sphere::distance2PointsOnSphere($this->lat, $this->long, $city->getLatitude(), $city->getLongitude());
    }
}
