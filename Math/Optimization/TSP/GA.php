<?php

namespace phpOMS\Math\Optimization\TSP;

class GA
{
    const MUTATION   = 15; /* 1000 = 100% */
    const TOURNAMENT = 5;
    const ELITISM    = true;

    private $cityPool = null;

    public function __construct(CityPool $pool)
    {
        $this->cityPool = $pool;
    }

    public function evolvePopulation(Population $population) : Population
    {
        $shift         = self::ELITISM ? 1 : 0;
        $newPopulation = new Population($this->cityPool, $count = $population->count(), false);

        $newPopulation->addTour($population->getFittest());

        for ($i = $shift; $i < $count; $i++) {
            $parent1 = $this->tournamentSelection($population);
            $parent2 = $this->tournamentSelection($population);
            $child   = $this->crossover($parent1, $parent2);

            $newPopulation->setTour($i, $child);
        }

        $count = $newPopulation->count();

        for ($i = $shift; $i < $count; $i++) {
            $this->mutate($newPopulation->getTour($i));
        }

        return $newPopulation;
    }

    public function crossover(Tour $tour1, Tour $tour2) : Tour
    {
        $child = new Tour($this->cityPool, false);

        $start = mt_rand(0, $tour1->count());
        $end   = mt_rand(0, $tour1->count());

        $count = $child->count(); /* $tour1->count() ???!!!! */

        for ($i = 0; $i < $count; $i++) {
            if ($start < $end && $i > $start && $i < $end) {
                $child->setCity($i, $tour1->getCity($i));
            } elseif ($start > $end && !($i < $start && $i > $end)) {
                $child->setCity($i, $tour1->getCity($i));
            }
        }

        $count = $tour2->count();

        for ($i = 0; $i < $count; $i++) {
            if (!$child->hasCity($tour2->getCity($i))) {
                for ($j = 0; $j < $child->count(); $j++) {
                    if ($child->getCity($j) === null) {
                        $child->setCity($j, $tour2->getCity($i));
                        break;
                    }
                }
            }
        }

        return $child;
    }

    private function mutate(Tour $tour)
    {
        $count = $tour->count();

        for ($pos1 = 0; $pos1 < $count; $pos1++) {
            if (mt_rand(0, 1000) < self::MUTATION) {
                $pos2 = mt_rand(0, $tour->count());

                /* Could be same pos! */
                $city1 = $tour->getCity($pos1);
                $city2 = $tour->getCity($pos2);

                /* swapping */
                $tour->setCity($pos1, $city2);
                $tour->setCity($pos2, $city1);
            }
        }
    }

    private function tournamentSelection(Population $population) : Tour
    {
        $tournament     = new Population($this->cityPool, self::TOURNAMENT, false);
        $populationSize = $population->count();

        for ($i = 0; $i < self::TOURNAMENT; $i++) {
            $tournament->addTour($population->getTour(mt_rand(0, $populationSize)));
        }

        return $tournament->getFittest();
    }

}
