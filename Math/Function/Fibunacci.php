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

namespace phpOMS\Math;
use phpOMS\Math\Number\Numbers;

/**
 * Well known functions class.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Fibunacci
{
    public static function isFibunacci(int $n)
    {
        return Numbers::isSquare(5 * $n ** 2 + 4) || Numbers::isSquare(5 * $n ** 2 - 4);
    }

    public static function fibunacci(int $n, int $start = 1) : int
    {
        if ($n < 2) {
            return 0;
        } elseif ($n < 4) {
            return $start;
        }

        $old_1 = 0;
        $old_2 = $start;
        $fib   = 0;

        for ($i = 4; $i < $n; $i++) {
            $fib   = $old_1 + $old_2;
            $old_1 = $old_2;
            $old_2 = $fib;
        }

        return $fib;
    }

    public static function binet(int $n) : int
    {
        return (int) (((1 + sqrt(5)) ** $n - (1 - sqrt(5)) ** $n) / (2 ** $n * sqrt(5)));
    }
}