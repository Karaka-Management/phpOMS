<?php

namespace phpOMS\Math\Optimization\TSP;

class BruteForce
{
    const LIMIT = 22;
    private $cityPool = null;


    public function __construct(CityPool $pool)
    {
        $this->cityPool = $pool;

        if ($this->cityPool->count() > self::LIMIT) {
            throw new \Exception('64 bit overflow');
        }
    }

    public function getSolution(\int $limit = 1) : Population
    {
        $population = new Population($this->cityPool, $limit, true);
        $cities     = $this->cityPool->getCities();

        $this->bruteForce($cities, new Tour($this->cityPool, false), $population);

        return $population;
    }

    private function bruteForce(array $cities, Tour $tour, Population $population)
    {
        if (count($cities) === 0) {
            $population->addTour($tour);
        }

        for ($i = 0; $i < count($cities); $i++) {
            $extended = clone $tour;
            $extended->addCity($cities[$i]);
            unset($cities[$i]);

            if ($population->getUnfittest()->getFitness() > $extended->getFitness()) {
                continue;
            }

            $this->bruteForce($cities, $extended, $population);
        }
    }
}
