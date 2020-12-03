<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
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
 * Cuboid shape.
 *
 * @package phpOMS\Math\Geometry\Shape\D3
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Cuboid implements D3ShapeInterface
{
    /**
     * Volume
     *
     * @param float $a Edge
     * @param float $b Edge
     * @param float $h Height
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVolume(float $a, float $b, float $h) : float
    {
        return $a * $b * $h;
    }

    /**
     * Surface area
     *
     * @param float $a Edge
     * @param float $b Edge
     * @param float $h Height
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSurface(float $a, float $b, float $h) : float
    {
        return 2 * ($a * $b + $a * $h + $b * $h);
    }
}
