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

use phpOMS\Math\Shape\D3\Sphere;

/**
 * City class.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class City
{
    /**
     * City name
     *
     * @var string
     * @since 1.0.0
     */
    private $name = '';

    /**
     * City longitude
     *
     * @var float
     * @since 1.0.0
     */
    private $long = 0.0;

    /**
     * City latitude
     *
     * @var float
     * @since 1.0.0
     */
    private $lat = 0.0;

    /**
     * Constructor.
     *
     * @param float  $lat  Latitude
     * @param float  $long Longitude
     * @param string $name City name
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(\float $lat, \float $long, \string $name)
    {
        $this->long = $long;
        $this->lat  = $lat;
        $this->name = $name;
    }

    /**
     * Get longitude.
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getLongitude() : \float
    {
        return $this->long;
    }

    /**
     * Get latitude.
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getLatitude() : \float
    {
        return $this->lat;
    }

    /**
     * Get name.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getName() : \string
    {
        return $this->name;
    }

    /**
     * Is equals to.
     *
     * @param City $city City
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function equals(City $city) : \bool
    {
        return $this->name === $city->getName() && $this->lat === $city->getLatitude() && $this->long === $city->getLatitude();
    }

    /**
     * Distance to city
     *
     * @param City $city City
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getDistanceTo(City $city) : \float
    {
        return Sphere::distance2PointsOnSphere($this->lat, $this->long, $city->getLatitude(), $city->getLongitude());
    }
}
