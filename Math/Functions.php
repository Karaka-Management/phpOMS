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

    public static function ackermann(int $m, int $n) 
    {
        if($m === 0) {
            return $n+1;
        } elseif($n === 0) {
            return self::ackermann($m-1, 1);
        }

        return ackermann($m-1, ackermann($m, $n-1));
    }

    public static function isSquare(int $x) {
        $goodMask; // 0xC840C04048404040 computed below

        for ($i = 0; $i < 64; ++$i) {
            $goodMask |= PHP_INT_MIN >>> ($i*$i);
        }

        // This tests if the 6 least significant bits are right.
        // Moving the to be tested bit to the highest position saves us masking.
        if ($goodMask << $x >= 0) return false;

        $numberOfTrailingZeros = self::countTrailingZeros($x);
        // Each square ends with an even number of zeros.
        if (($numberOfTrailingZeros & 1) !== 0) return false;

        $x >>= $numberOfTrailingZeros;
        // Now x is either 0 or odd.
        // In binary each odd square ends with 001.
        // Postpone the sign test until now; handle zero in the branch.
        if (($x&7) != 1 | $x <= 0) return $x === 0;
        // Do it in the classical way.
        // The correctness is not trivial as the conversion from long to double is lossy!
        $tst = (int) sqrt($x);

        return $tst * $tst == $x;
    }

    public static function countTrailingZeros(int $n) : int
    {
        $count = 0;
        while ($n !== 0) {
            if ($n & 1 == 1)  {
                break;
            } else {
                $count++;
                $n = $n >> 1;
            }
        }

        return $count;
    }

    public static function greatestCommonDivisor(int $n, int $m) : int
    {
        while(true) {
            if($n === $m) {
                return $m;
            } if($n > $m) {
                $n -= $m;
            } else {
                $m -= $n;
            }
        }
    }

    public static function invMod($a,$n){
        if ($n < 0) {
            $n = -$n;
        }

        if ($a < 0) { 
            $a = $n - (-$a % $n);
        }

        $t = 0; 
        $nt = 1; 
        $r = $n; 
        $nr = $a % $n;

        while ($nr != 0) {
            $quot = intval($r/$nr);
            $tmp = $nt;  
            $nt = $t - $quot*$nt;  
            $t = $tmp;
            $tmp = $nr;  
            $nr = $r - $quot*$nr;  
            $r = $tmp;
        }

        if ($r > 1) {
            return -1;
        }

        if ($t < 0) {
            $t += $n;
        }

        return $t;
    }
}
