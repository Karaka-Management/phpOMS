<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Math\Geometry\Shape\D2
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Geometry\Shape\D2;

/**
 * Trapezoid shape.
 *
 * @package phpOMS\Math\Geometry\Shape\D2
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Trapezoid implements D2ShapeInterface
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
     *       --- a ----
     *     /  |        \
     *    c   h         d
     *  /     |          \
     * -------- b ---------
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
        return ($a + $b) / 2 * $h;
    }

    /**
     * Perimeter
     *
     *       --- a ----
     *     /  |        \
     *    c   h         d
     *  /     |          \
     * -------- b ---------
     *
     * @param float $a Edge
     * @param float $b Edge
     * @param float $c Edge
     * @param float $d Edge
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPerimeter(float $a, float $b, float $c, float $d) : float
    {
        return $a + $b + $c + $d;
    }

    /**
     * Height
     *
     *       --- a ----
     *     /  |        \
     *    c   h         d
     *  /     |          \
     * -------- b ---------
     *
     * @param float $area Area
     * @param float $a    Edge
     * @param float $b    Edge
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getHeight(float $area, float $a, float $b) : float
    {
        return 2 * $area / ($a + $b);
    }

    /**
     * A
     *
     *       --- a ----
     *     /  |        \
     *    c   h         d
     *  /     |          \
     * -------- b ---------
     *
     * @param float $area Area
     * @param float $h    Height
     * @param float $b    Edge
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getA(float $area, float $h, float $b) : float
    {
        return 2 * $area / $h - $b;
    }

    /**
     * B
     *
     *       --- a ----
     *     /  |        \
     *    c   h         d
     *  /     |          \
     * -------- b ---------
     *
     * @param float $area Area
     * @param float $h    Height
     * @param float $a    Edge
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getB(float $area, float $h, float $a) : float
    {
        return 2 * $area / $h - $a;
    }

    /**
     * C
     *
     *       --- a ----
     *     /  |        \
     *    c   h         d
     *  /     |          \
     * -------- b ---------
     *
     * @param float $perimeter Perimeter
     * @param float $a         Edge
     * @param float $b         Edge
     * @param float $d         Edge
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getC(float $perimeter, float $a, float $b, float $d) : float
    {
        return $perimeter - $a - $b - $d;
    }

    /**
     * D
     *
     *       --- a ----
     *     /  |        \
     *    c   h         d
     *  /     |          \
     * -------- b ---------
     *
     * @param float $perimeter Perimeter
     * @param float $a         Edge
     * @param float $b         Edge
     * @param float $c         Edge
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getD(float $perimeter, float $a, float $b, float $c) : float
    {
        return $perimeter - $a - $b - $c;
    }
}
