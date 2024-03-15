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
 * Tetraedron shape.
 *
 * @package phpOMS\Math\Geometry\Shape\D3
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Tetrahedron implements D3ShapeInterface
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
     * Volume
     *
     * @param float $a Edge
     *
     * @return float
     *
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public static function getFaceArea(float $a) : float
    {
        return \sqrt(3) / 4 * $a ** 2;
    }
}
