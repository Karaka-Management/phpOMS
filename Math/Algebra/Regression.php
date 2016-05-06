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
 * Regression class.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Regression
{
    /**
     * Calculate linear regression.
     *
     * Example: ([1, 4, 6, 8, 9], [5, 3, 8, 6, 2])
     *
     * @param array $x X coordinates
     * @param array $y Y coordinates
     *
     * @return array
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function linearRegression(array $x, array $y) : array
    {
        $count = count($x);

        if ($count !== count($y)) {
            throw new \Exception('Dimensions');
        }

        $xSum = array_sum($x);
        $ySum = array_sum($y);

        $xxSum = 0;
        $xySum = 0;

        for ($i = 0; $i < $count; $i++) {

            $xySum += ($x[$i] * $y[$i]);
            $xxSum += ($x[$i] * $x[$i]);
        }

        $m = (($count * $xySum) - ($xSum * $ySum)) / (($count * $xxSum) - ($xSum * $xSum));
        $b = ($ySum - ($m * $xSum)) / $count;

        return ['m' => $m, 'b' => $b];
    }
}
