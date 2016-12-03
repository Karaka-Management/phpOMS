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
namespace phpOMS\Math\Shape\D3;

/**
 * Sphere shape.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Sphere implements D3ShapeInterface
{

    public function __construct(float $radius)
    {
        $this->radius = $radius;
    }

    /**
     * Calculating the distance between two points on a sphere
     *
     * @param float $latStart  Latitude of start point in deg
     * @param float $longStart Longitude of start point in deg
     * @param float $latEnd    Latitude of target point in deg
     * @param float $longEnd   Longitude of target point in deg
     * @param float $radius    Sphere radius (6371000 = earth)
     *
     * @return float Distance between points in meter
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function distance2PointsOnSphere(float $latStart, float $longStart, float $latEnd, float $longEnd, float $radius = 6371000.0) : float
    {
        $latFrom = deg2rad($latStart);
        $lonFrom = deg2rad($longStart);
        $latTo   = deg2rad($latEnd);
        $lonTo   = deg2rad($longEnd);

        //$latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = pow(cos($latTo) * sin($lonDelta), 2) + pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        // Approximation (very good for short distances)
        // $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $radius;
    }

    public static function byRadius(float $r) : Sphere
    {
        return new self($r);
    }

    public static function byVolume(float $v) : Sphere
    {
        return new self(self::getRadiusByVolume($v));
    }

    /**
     * Radius
     *
     * @param float $V Volume
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getRadiusByVolume(float $V) : float
    {
        return pow($V * 3 / (4 * pi()), 1 / 3);
    }

    public static function bySurface(float $s) : Sphere
    {
        return new self(self::getRadiusBySurface($s));
    }

    /**
     * Radius
     *
     * @param float $S Surface
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getRadiusBySurface(float $S) : float
    {
        return sqrt($S / (4 * pi()));
    }

    public function getVolume() : float
    {
        return self::getVolumeByRadius($this->radius);
    }

    /**
     * Volume
     *
     * @param float $r Radius
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getVolumeByRadius(float $r) : float
    {
        return 4 / 3 * pi() * $r ** 3;
    }

    public function getRadius() : float
    {
        return $this->radius;
    }

    public function getSurface() : float
    {
        return self::getSurfaceByRadius($this->radius);
    }

    /**
     * Surface area
     *
     * @param float $r Radius
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getSurfaceByRadius(float $r) : float
    {
        return 4 * pi() * $r ** 2;
    }
}
