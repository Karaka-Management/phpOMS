<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Math\Geometry\Shape\D3
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Geometry\Shape\D3;

/**
 * Tetraedron shape.
 *
 * @package    phpOMS\Math\Geometry\Shape\D3
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
final class Tetrahedron implements D3ShapeInterface
{

    /**
     * Volume
     *
     * @param float $a Edge
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getVolume(float $a) : float
    {
        return $a ** 3 / (6 * \sqrt(2));
    }

    /**
     * Surface area
     *
     * @param float $a Edge
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getSurface(float $a) : float
    {
        return \sqrt(3) * $a ** 2;
    }

    /**
     * Lateral surface area
     *
     * @param float $a Edge
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getFaceArea(float $a) : float
    {
        return \sqrt(3) / 4 * $a ** 2;
    }
}
