<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Geometry\Shape\D3
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Geometry\Shape\D3;

/**
 * Cylinder shape.
 *
 * @package phpOMS\Math\Geometry\Shape\D3
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class Cylinder implements D3ShapeInterface
{
    /**
     * Volume
     *
     * @param float $r Radius
     * @param float $h Height
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVolume(float $r, float $h) : float
    {
        return \M_PI * $r ** 2 * $h;
    }

    /**
     * Surface area
     *
     * @param float $r Radius
     * @param float $h Height
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSurface(float $r, float $h) : float
    {
        return 2 * \M_PI * ($r * $h + $r ** 2);
    }

    /**
     * Lateral surface area
     *
     * @param float $r Radius
     * @param float $h Height
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getLateralSurface(float $r, float $h) : float
    {
        return 2 * \M_PI * $r * $h;
    }
}
