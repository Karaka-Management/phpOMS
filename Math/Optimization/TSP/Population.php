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
 * Population class.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Population implements \Countable
{
    /**
     * Tours
     *
     * @var array
     * @since 1.0.0
     */
    private $tours = [];

    /**
     * Constructor.
     *
     * @param CityPool $pool       City pool
     * @param int      $size       Population size
     * @param bool     $initialize Initialize with random tours
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(CityPool $pool, \int $size, \bool $initialize = false)
    {
        if ($initialize) {
            for ($i = 0; $i < $size; $i++) {
                $this->tours[] = new Tour($pool, true);
            }
        }
    }

    /**
     * Insert Tour at position.
     *
     * @param int  $index Position to insert at
     * @param Tour $tour  Tour to insert
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function insertTourAt(\int $index, Tour $tour)
    {
        $this->tours = array_slice($this->tours, 0, $index) + [$tour] + array_slice($this->tours, $index);
    }

    /**
     * Set tour at position
     *
     * @param int  $index Position to set/replace
     * @param Tour $tour  Tour to set
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setTour(\int $index, Tour $tour)
    {
        $this->tours[$index] = $tour;
        asort($this->tours);
    }

    /**
     * Add tour
     *
     * @param Tour $tour Tour to add
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addTour(Tour $tour)
    {
        $this->tours[] = $tour;
    }

    /**
     * Get tour
     *
     * @param int $index Index of tour
     *
     * @return null|Tour
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getTour(\int $index)
    {
        return $this->tours[$index] ?? null;
    }

    /**
     * Get fittest tour
     *
     * @return Tour
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
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

    /**
     * Get unfittest tour
     *
     * @return Tour
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
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

    /**
     * Get tour count
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function count() : \int
    {
        return count($this->tours);
    }
}
