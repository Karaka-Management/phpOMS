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
namespace phpOMS\Math\Algebra;

class Sphere
{

    /**
     * Calculating the distance between two points on a sphere
     *
     * @param float $latStart  Latitude of start point in deg
     * @param float $longStart Longitude of start point in deg
     * @param float $latEnd    Latitude of target point in deg
     * @param float $longEnd   Longitude of target point in deg
     * @param float $radius    Sphere radius
     *
     * @return float Distance between points in meter
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function distance2PointsOnSphere(\float $latStart, \float $longStart, \float $latEnd, \float $longEnd, \float $radius = 6371000.0) : \float
    {
        $latFrom = deg2rad($latStart);
        $lonFrom = deg2rad($longStart);
        $latTo   = deg2rad($latEnd);
        $lonTo   = deg2rad($longEnd);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a        = pow(cos($latTo) * sin($lonDelta), 2) + pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b        = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        // Approximation (very good for short distances)
        // $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $radius;
    }
}
