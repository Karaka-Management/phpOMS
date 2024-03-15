<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Geometry\Shape\D3
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Geometry\Shape\D3;

use phpOMS\Math\Geometry\Shape\D2\Polygon;

/**
 * Prism shape.
 *
 * @package phpOMS\Math\Geometry\Shape\D3
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Prism implements D3ShapeInterface
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Get volume of regular polygon prism by side length
     *
     * @param float $length Side length
     * @param int   $sides  Number of sides
     * @param float $h      Height
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVolumeRegularLength(float $length, int $sides, float $h) : float
    {
        return Polygon::getRegularAreaByLength($length, $sides) * $h;
    }

    /**
     * Get volume area of regular polygon prism by radius
     *
     * @param float $r     Radius
     * @param int   $sides Number of sides
     * @param float $h     Height
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVolumeRegularRadius(float $r, int $sides, float $h) : float
    {
        return Polygon::getRegularAreaByRadius($r, $sides) * $h;
    }

    /**
     * Get surface area of regular polygon prism by side length
     *
     * @param float $length Side length
     * @param int   $sides  Number of sides
     * @param float $h      Height
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSurfaceRegularLength(float $length, int $sides, float $h) : float
    {
        return Polygon::getRegularAreaByLength($length, $sides) * 2 + $length * $sides * $h;
    }
}
