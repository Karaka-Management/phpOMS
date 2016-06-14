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
 * Triangle shape.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Triangle implements D2ShapeInterface
{

    /**
     * Area
     *
     *     .
     *    /|\
     *  a  h c
     * /   |  \
     * ----b---
     *
     * @param float $b Edge
     * @param float $h Height
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getSurface(float $b, float $h)
    {
        return $h * $b / 2;
    }

    /**
     * Perimeter
     *
     * @param float $a Edge
     * @param float $b Edge
     * @param float $c Edge
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getPerimeter(float $a, float $b, float $c)
    {
        return $a + $b + $c;
    }

    /**
     * Diagonal
     *
     * @param float $area Area
     * @param float $b    Edge
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getHeight(float $area, float $b)
    {
        return 2 * $area / $b;
    }
}
