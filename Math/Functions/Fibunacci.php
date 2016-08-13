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

namespace phpOMS\Math\Functions;

use phpOMS\Math\Number\Numbers;

/**
 * Well known functions class.
 *
 * @category   Framework
 * @package    phpOMS\Math\Function
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Fibunacci
{

    /**
     * Is fibunacci number.
     *
     * @param int $n Integer
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function isFibunacci(int $n) : bool
    {
        return Numbers::isSquare(5 * $n ** 2 + 4) || Numbers::isSquare(5 * $n ** 2 - 4);
    }

    /**
     * Get n-th fibunacci number.
     *
     * @param int $n     n-th number
     * @param int $start Start value
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function fibunacci(int $n, int $start = 1) : int
    {
        if ($n < 3) {
            return $start;
        }

        $old_1 = $start;
        $old_2 = $start;
        $fib   = 0;

        for ($i = 2; $i < $n; $i++) {
            $fib   = $old_1 + $old_2;
            $old_1 = $old_2;
            $old_2 = $fib;
        }

        return $fib;
    }

    /**
     * Calculate n-th fibunacci with binets formula.
     *
     * @param int $n n-th number
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function binet(int $n) : int
    {
        return (int) (((1 + sqrt(5)) ** $n - (1 - sqrt(5)) ** $n) / (2 ** $n * sqrt(5)));
    }
}