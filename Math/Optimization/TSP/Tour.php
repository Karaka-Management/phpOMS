<?php

namespace phpOMS\Math\Optimization\TSP;

class Tour implements \Countable
{
    private $cities   = [];
    private $fitness  = 0.0;
    private $distance = 0.0;

    private $cityPool = null;

    public function __construct(CityPool $pool, \bool $initialise = false)
    {
        $this->cityPool = $pool;

        if ($initialise) {
            $this->cities = $this->cityPool->getCities();
            shuffle($this->cities);
        }
    }

    public function getCity($index)
    {
        return array_values($this->cities)[$index] ?? null;
    }

    public function getFitness() : \float
    {
        if ($this->fitness === 0.0 && ($distance = $this->getDistance()) !== 0.0) {
            $this->fitness = 1 / $distance;
        }

        return $this->fitness;
    }

    public function addCity(City $city)
    {
        $this->cities[] = $city;

        $this->fitness  = 0.0;
        $this->distance = 0.0;
    }

    public function setCity(\int $index, City $city)
    {
        $this->cities[$index] = $city;
        asort($this->cities);

        $this->fitness  = 0.0;
        $this->distance = 0.0;
    }

    public function getDistance() : \float
    {
        if ($this->distance === 0.0) {
            $distance = 0.0;

            $count = count($this->cities);

            for ($i = 0; $i < $count; $i++) {
                $dest = ($i + 1 < $count) ? $this->cities[$i + 1] : $this->cities[0];

                $distance += $this->cities[$i]->getDistanceTo($dest);
            }

            $this->distance = $distance;
        }

        return $this->distance;
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
