<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Math\Shape\D3;

/**
 * Rectangular pyramid shape.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class RectangularPyramid implements D3ShapeInterface
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public static function getSurface(float $a, float $b, float $h) : float
    {
        return $a * $b + $a * sqrt(($b / 2) ** 2 + $h ** 2) + $b * sqrt(($a / 2) ** 2 + $h ** 2);
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
     * @since  1.0.0
     */
    public static function getLateralSurface(float $a, float $b, float $h) : float
    {
        return $a * sqrt(($b / 2) ** 2 + $h ** 2) + $b * sqrt(($a / 2) ** 2 + $h ** 2);
    }
}
