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
class Functions
{
    /**
     * Calculate gammar function value.
     *
     * Example: (7)
     *
     * @param int $k Variable
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getGammaInteger(int $k) : int
    {
        return self::fact($k - 1);
    }

    /**
     * Calculate gammar function value.
     *
     * Example: (7, 2)
     *
     * @param int $n     Factorial upper bound
     * @param int $start Factorial starting value
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function fact(int $n, int $start = 1) : int
    {
        $fact = 1;

        for ($i = $start; $i < $n + 1; $i++) {
            $fact *= $i;
        }

        return $fact;
    }

    /**
     * Calculate binomial coefficient
     *
     * Algorithm optimized for large factorials without the use of big int or string manipulation.
     *
     * Example: (7, 2)
     *
     * @param int $n
     * @param int $k
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function binomialCoefficient(int $n, int $k) : int
    {
        $max = max([$k, $n - $k]);
        $min = min([$k, $n - $k]);

        $fact  = 1;
        $range = array_reverse(range(1, $min));

        for ($i = $max + 1; $i < $n + 1; $i++) {
            $div = 1;
            foreach ($range as $key => $d) {
                if ($i % $d === 0) {
                    $div = $d;

                    unset($range[$key]);
                    break;
                }
            }

            $fact *= $i / $div;
        }

        $fact2 = 1;

        foreach ($range as $d) {
            $fact2 *= $d;
        }

        return $fact / $fact2;
    }

    /**
     * Calculate ackermann function.
     *
     * @param int $m
     * @param int $n
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function ackermann(int $m, int $n) : int
    {
        if ($m === 0) {
            return $n + 1;
        } elseif ($n === 0) {
            return self::ackermann($m - 1, 1);
        }

        return self::ackermann($m - 1, self::ackermann($m, $n - 1));
    }

    /**
     * Applying abs to every array value
     *
     * @param array $values Numeric values
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function abs(array $values) : array
    {
        $abs = [];

        foreach ($values as $value) {
            $abs[] = abs($value);
        }

        return $abs;
    }

    /**
     * Calculate inverse modular.
     *
     * @param int $a
     * @param int $n Modulo
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function invMod(int $a, int $n)
    {
        if ($n < 0) {
            $n = -$n;
        }

        if ($a < 0) {
            $a = $n - (-$a % $n);
        }

        $t  = 0;
        $nt = 1;
        $r  = $n;
        $nr = $a % $n;

        while ($nr != 0) {
            $quot = (int) ($r / $nr);
            $tmp  = $nt;
            $nt   = $t - $quot * $nt;
            $t    = $tmp;
            $tmp  = $nr;
            $nr   = $r - $quot * $nr;
            $r    = $tmp;
        }

        if ($r > 1) {
            return -1;
        }

        if ($t < 0) {
            $t += $n;
        }

        return $t;
    }

    /**
     * Modular implementation for negative values.
     *
     * @param int $a
     * @param int $b
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function mod($a, $b)
    {
        if ($a < 0) {
            return ($a + $b) % $b;
        }

        return $a % $b;
    }

    /**
     * Check if value is odd.
     *
     * @param int $a Value to test
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function isOdd($a) : bool
    {
        if ($a & 1) {
            return true;
        }

        return false;
    }

    /**
     * Check if value is even.
     *
     * @param int $a Value to test
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function isEven($a) : bool
    {
        if ($a & 1) {
            return false;
        }

        return true;
    }

    /**
     * Gets the relative position on a circular construct.
     *
     * @example The relative fiscal month (August) in a company where the fiscal year starts in July.
     * @example 2 = getRelativeDegree(8, 12, 7);
     *
     * @param int $a Value to test
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getRelativeDegree($value, $length, $start = 0)
    {
        return abs(self::mod($value - $start, $length));
    }
}
