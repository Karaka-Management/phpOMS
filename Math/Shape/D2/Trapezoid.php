<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Math\Algebra;

/**
 * Trapezoid shape.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Trapezoid
{

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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getArea(float $a, float $b, float $h)
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getPerimeter(float $a, float $b, float $c, float $d)
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getHeight(float $area, float $a, float $b)
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getA(float $area, float $h, float $b)
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getB(float $area, float $h, float $a)
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getC(float $perimeter, float $a, float $b, float $d)
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getD(float $perimeter, float $a, float $b, float $c)
    {
        return $perimeter - $a - $b - $c;
    }
}
