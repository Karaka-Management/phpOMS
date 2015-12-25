<?php

namespace phpOMS\Math\Optimization\TSP;

class Population implements \Countable
{
    private $tours = [];

    public function __construct(CityPool $pool, \int $size, \bool $initialise = false)
    {
        if ($initialise) {
            for ($i = 0; $i < $size; $i++) {
                $this->tours[] = new Tour($pool, true);
            }
        }
    }

    public function insertTourAt(\int $index, Tour $tour)
    {
        $this->tours = array_slice($this->tours, 0, $index) + [$tour] + array_slice($this->tours, $index);
    }

    public function setTour(\int $index, Tour $tour)
    {
        $this->tours[$index] = $tour;
        asort($this->tours);
    }

    public function addTour(Tour $tour)
    {
        $this->tours[] = $tour;
    }

    public function getTour(\int $index)
    {
        return $this->tours[$index] ?? null;
    }

    public function getFittest() : Tour
    {
        $fittest = $this->tours[0];
        $count   = count($this->tours);

        for ($i = 1; $i < $count; $i++) {
            if ($fittest->getFitness() <= $this->tours[$i]->getFitness()) {
                $fittest = $this->tours[$i];
            }
        }

        return $fittest;
    }

    public function getUnfittest() : Tour
    {
        $unfittest = $this->tours[0];
        $count     = count($this->tours);

        for ($i = 1; $i < $count; $i++) {
            if ($unfittest->getFitness() >= $this->tours[$i]->getFitness()) {
                $unfittest = $this->tours[$i];
            }
        }

        return $unfittest;
    }

    public function count() : \int
    {
        return count($this->tours);
    }
}
