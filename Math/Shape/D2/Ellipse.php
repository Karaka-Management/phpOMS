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
namespace phpOMS\Math\Shape\D2;

/**
 * Ellipse shape.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Ellipse implements D2ShapeInterface
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getSurface(float $a, float $b) : float
    {
        return pi() * $a * $b;
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getPerimeter(float $a, float $b) : float
    {
        return pi() * ($a + $b) * (3 * ($a - $b) ** 2 / (($a + $b) ** 2 * (sqrt(-3 * ($a - $b) ** 2 / (($a + $b) ** 2) + 4) + 10)) + 1);
    }
}
