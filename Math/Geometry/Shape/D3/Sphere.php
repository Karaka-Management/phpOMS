<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Math\Geometry\Shape\D3
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Geometry\Shape\D3;

/**
 * Sphere shape.
 *
 * @package phpOMS\Math\Geometry\Shape\D3
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Sphere implements D3ShapeInterface
{
    /**
     * Radius.
     *
     * @var float
     * @since 1.0.0
     */
    private float $radius = 0.0;

    /**
     * Constructor.
     *
     * @param float $radius Sphere radius
     *
     * @since 1.0.0
     */
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
     * @since 1.0.0
     */
    public static function distance2PointsOnSphere(float $latStart, float $longStart, float $latEnd, float $longEnd, float $radius = 6371000.0) : float
    {
        $latFrom = \deg2rad($latStart);
        $lonFrom = \deg2rad($longStart);
        $latTo   = \deg2rad($latEnd);
        $lonTo   = \deg2rad($longEnd);

        //$latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = \pow(\cos($latTo) * \sin($lonDelta), 2) + \pow(\cos($latFrom) * \sin($latTo) - \sin($latFrom) * \cos($latTo) * \cos($lonDelta), 2);
        $b = \sin($latFrom) * \sin($latTo) + \cos($latFrom) * \cos($latTo) * \cos($lonDelta);

        $angle = \atan2(\sqrt($a), $b);
        // Approximation (very good for short distances)
        // $angle = 2 * asin(\sqrt(\pow(\sin($latDelta / 2), 2) + \cos($latFrom) * \cos($latTo) * \pow(\sin($lonDelta / 2), 2)));

        return $angle * $radius;
    }

    /**
     * Create sphere by radius
     *
     * @param float $r Radius
     *
     * @return Sphere
     *
     * @since 1.0.0
     */
    public static function byRadius(float $r) : self
    {
        return new self($r);
    }

    /**
     * Create sphere by volume
     *
     * @param float $v Sphere volume
     *
     * @return Sphere
     *
     * @since 1.0.0
     */
    public static function byVolume(float $v) : self
    {
        return new self(self::getRadiusByVolume($v));
    }

    /**
     * Radius
     *
     * @param float $v Volume
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getRadiusByVolume(float $v) : float
    {
        return \pow($v * 3 / (4 * \M_PI), 1 / 3);
    }

    /**
     * Create sphere by surface
     *
     * @param float $s Sphere surface
     *
     * @return Sphere
     *
     * @since 1.0.0
     */
    public static function bySurface(float $s) : self
    {
        return new self(self::getRadiusBySurface($s));
    }

    /**
     * Get radius by sphere
     *
     * @param float $S Surface
     *
     * @return float
     *
     * @since  1.0.0
     *
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public static function getRadiusBySurface(float $S) : float
    {
        return \sqrt($S / (4 * \M_PI));
    }

    /**
     * Get volume
     *
     * @return float Sphere volume
     *
     * @since 1.0.0
     */
    public function getVolume() : float
    {
        return self::getVolumeByRadius($this->radius);
    }

    /**
     * Get sphere volume by radius
     *
     * @param float $r Radius
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVolumeByRadius(float $r) : float
    {
        return 4 / 3 * \M_PI * $r ** 3;
    }

    /**
     * Get radius
     *
     * @return float Sphere radius
     *
     * @since 1.0.0
     */
    public function getRadius() : float
    {
        return $this->radius;
    }

    /**
     * Get surface
     *
     * @return float Sphere surface
     *
     * @since 1.0.0
     */
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
     * @since 1.0.0
     */
    public static function getSurfaceByRadius(float $r) : float
    {
        return 4 * \M_PI * $r ** 2;
    }
}
