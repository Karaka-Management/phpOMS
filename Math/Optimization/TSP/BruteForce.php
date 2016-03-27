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
 * TSP solution with brute force.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class BruteForce
{
    /**
     * City limit (for factorial calculation).
     *
     * @var float
     * @since 1.0.0
     */
    const LIMIT = 22;

    /**
     * City pool.
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
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(CityPool $pool)
    {
        $this->cityPool = $pool;

        if ($this->cityPool->count() > self::LIMIT) {
            throw new \Exception('64 bit overflow');
        }
    }

    /**
     * Calculate best routes.
     *
     * @param int $limit Amount of best routes
     *
     * @return Population
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getSolution(int $limit = 1) : Population
    {
        $population = new Population($this->cityPool, $limit, true);
        $cities     = $this->cityPool->getCities();

        $this->bruteForce($cities, new Tour($this->cityPool, false), $population);

        return $population;
    }

    /**
     * Bruteforce best solutions.
     *
     * @param array      $cities     Cities
     * @param Tour       $tour       Current (potential) optimal tour
     * @param Population $population Population of tours
     *
     * @return Population
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function bruteForce(array $cities, Tour $tour, Population $population)
    {
        if (count($cities) === 0) {
            $population->addTour($tour);
        }

        $count = count($cities);
        for ($i = 0; $i < $count; $i++) {
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
