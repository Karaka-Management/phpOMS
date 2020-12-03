<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Math\Geometry\Shape\D2
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Geometry\Shape\D2;

/**
 * Ellipse shape.
 *
 * @package phpOMS\Math\Geometry\Shape\D2
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Ellipse implements D2ShapeInterface
{
    /**
     * Area
     *
     *          |
     *          b
     * -------a-|----
     *          |
     *
     * @param float $a Axis
     * @param float $b Axis
     *
     * @return float Distance between points in meter
     *
     * @since 1.0.0
     */
    public static function getSurface(float $a, float $b) : float
    {
        return \M_PI * $a * $b;
    }

    /**
     * Circumference
     *
     *          |
     *          b
     * -------a-|----
     *          |
     *
     * @param float $a Axis
     * @param float $b Axis
     *
     * @return float Distance between points in meter
     *
     * @since 1.0.0
     */
    public static function getPerimeter(float $a, float $b) : float
    {
        return \M_PI * ($a + $b) * (3 * ($a - $b) ** 2 / (($a + $b) ** 2 * (\sqrt(-3 * ($a - $b) ** 2 / (($a + $b) ** 2) + 4) + 10)) + 1);
    }
}
