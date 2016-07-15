<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */

namespace phpOMS\Math\Optimization\TSP;

/**
 * TSP solution by genetic algorithm.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class GA
{
    /**
     * Mutation percentage
     *
     * @var int
     * @since 1.0.0
     */
    const MUTATION = 15; /* 1000 = 100% */

    /**
     * Tournaments
     *
     * @var int
     * @since 1.0.0
     */
    const TOURNAMENT = 5;

    /**
     * Elitism
     *
     * @var bool
     * @since 1.0.0
     */
    const ELITISM = true;

    /**
     * City pool
     *
     * @var CityPool
     * @since 1.0.0
     */
    private $cityPool = null;

    /**
     * Constructor.
     *
     * @param CityPool $pool City pool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(CityPool $pool)
    {
        $this->cityPool = $pool;
    }

    /**
     * Evolve population.
     *
     * @param Population $population Population to eveolve
     *
     * @return Population
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function evolvePopulation(Population $population) : Population
    {
        $shift         = self::ELITISM ? 1 : 0;
        $newPopulation = new Population($this->cityPool, $count = $population->count(), false);

        $newPopulation->add($population->getFittest());

        for ($i = $shift; $i < $count; $i++) {
            $parent1 = $this->tournamentSelection($population);
            $parent2 = $this->tournamentSelection($population);
            $child   = $this->crossover($parent1, $parent2);

            $newPopulation->set($i, $child);
        }

        $count = $newPopulation->count();

        for ($i = $shift; $i < $count; $i++) {
            $this->mutate($newPopulation->get($i));
        }

        return $newPopulation;
    }

    /**
     * Find fittest
     *
     * @param Population $population Population to evaluate
     *
     * @return Tour
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function tournamentSelection(Population $population) : Tour
    {
        $tournament     = new Population($this->cityPool, self::TOURNAMENT, false);
        $populationSize = $population->count();

        for ($i = 0; $i < self::TOURNAMENT; $i++) {
            $tournament->add($population->get(mt_rand(0, $populationSize)));
        }

        return $tournament->getFittest();
    }

    /**
     * Crossover tours
     *
     * @param Tour $tour1 Tour 1
     * @param Tour $tour2 Tour 2
     *
     * @return Tour
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
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

    /**
     * Mutate tour
     *
     * @param Tour $tour Tour to mutate
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
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

}
