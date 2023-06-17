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

/**
 * Rectangular pyramid shape.
 *
 * @package phpOMS\Math\Geometry\Shape\D3
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class RectangularPyramid implements D3ShapeInterface
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
        return $a * $b * $h / 3;
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
        return $a * $b + $a * \sqrt(($b / 2) ** 2 + $h ** 2) + $b * \sqrt(($a / 2) ** 2 + $h ** 2);
    }

    /**
     * Lateral surface area
     *
     * @param float $a Edge
     * @param float $b Edge
     * @param float $h Height
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getLateralSurface(float $a, float $b, float $h) : float
    {
        return $a * \sqrt(($b / 2) ** 2 + $h ** 2) + $b * \sqrt(($a / 2) ** 2 + $h ** 2);
    }
}
