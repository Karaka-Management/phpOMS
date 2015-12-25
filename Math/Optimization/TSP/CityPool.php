<?php

namespace phpOMS\Math\Optimization\TSP;

class CityPool implements \Countable
{
    private $cities = [];

    public function __construct($cities = [])
    {
        $this->cities = $cities;
    }

    public function addCity(City $city)
    {
        $this->cities[$city->getName() . $city->getLatitude() . $city->getLongitude()] = $city;
    }

    public function getCity($index) : City
    {
        return array_values($this->cities)[$index];
    }

    public function getCities() : array
    {
        return $this->cities;
    }

    public function hasCity(City $city) : \bool
    {
        foreach ($this->cities as $c) {
            if ($c->equals($city)) {
                return true;
            }
        }

        return false;
    }

    public function count() : \int
    {
        return count($this->cities);
    }
}
