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
 * Rectangle shape.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Rectangle
{

    /**
     * Area
     *
     * @param float $a Edge
     * @param float $b Edge
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getArea(float $a, float $b)
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getPerimeter(float $a, float $b)
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getDiagonal(float $a, float $b)
    {
        return sqrt($a * $a + $b * $b);
    }
}
