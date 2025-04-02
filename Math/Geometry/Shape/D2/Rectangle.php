<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Math\Geometry\Shape\D2
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Geometry\Shape\D2;

/**
 * Rectangle shape.
 *
 * @package phpOMS\Math\Geometry\Shape\D2
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Rectangle implements D2ShapeInterface
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
     * Area
     *
     * @param float $a Edge
     * @param float $b Edge
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSurface(float $a, float $b) : float
    {
        return $a * $b;
    }

    /**
     * Perimeter
     *
     * @param float $a Edge
     * @param float $b Edge
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPerimeter(float $a, float $b) : float
    {
        return 2 * ($a + $b);
    }

    /**
     * Diagonal
     *
     * @param float $a Edge
     * @param float $b Edge
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getDiagonal(float $a, float $b) : float
    {
        return \sqrt($a * $a + $b * $b);
    }
}
