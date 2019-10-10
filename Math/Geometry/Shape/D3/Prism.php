<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Math\Geometry\Shape\D3
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);
namespace phpOMS\Math\Geometry\Shape\D3;

/**
 * Prism shape.
 *
 * @package phpOMS\Math\Geometry\Shape\D3
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Prism implements D3ShapeInterface
{
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
