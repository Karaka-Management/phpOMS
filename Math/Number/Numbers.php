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

namespace phpOMS\Math\Number;

/**
 * Numbers class.
 *
 * @category   Framework
 * @package    Utils
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Numbers
{
    /**
     * Is perfect number?
     *
     * @param int $n Number to test
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function perfect(int $n) : bool
    {
        $sum = 0;

        for ($i = 1; $i < $n; $i++) {
            if ($n % $i == 0) {
                $sum += $i;
            }
        }

        return $sum == $n;
    }

    /**
     * Is self describing number?
     *
     * @param int $n Number to test
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function isSelfdescribing(int $n) : bool
    {
        $split = str_split($n);
        foreach ($split as $place => $value) {
            if (substr_count($n, $place) != $value) {
                return false;
            }
        }

        return true;
    }

    /**
     * Is square number?
     *
     * @param int $n Number to test
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function isSquare(int $n) : bool
    {
        $goodMask = 0xC840C04048404040; // 0xC840C04048404040 computed below

        for ($i = 0; $i < 64; ++$i) {
            $goodMask |= PHP_INT_MIN >> ($i * $i);
        }

        // This tests if the 6 least significant bits are right.
        // Moving the to be tested bit to the highest position saves us masking.
        if ($goodMask << $n >= 0) {
            return false;
        }

        $numberOfTrailingZeros = self::countTrailingZeros($n);
        // Each square ends with an even number of zeros.
        if (($numberOfTrailingZeros & 1) !== 0) {
            return false;
        }

        $n >>= $numberOfTrailingZeros;
        // Now x is either 0 or odd.
        // In binary each odd square ends with 001.
        // Postpone the sign test until now; handle zero in the branch.
        if (($n & 7) != 1 | $n <= 0) {
            return $n === 0;
        }
        // Do it in the classical way.
        // The correctness is not trivial as the conversion from long to double is lossy!
        $tst = (int) sqrt($n);

        return $tst * $tst === $n;
    }

    /**
     * Count trailling zeros
     *
     * @param int $n Number to test
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function countTrailingZeros(int $n) : int
    {
        $count = 0;
        while ($n !== 0) {
            if ($n & 1 == 1) {
                break;
            } else {
                $count++;
                $n = $n >> 1;
            }
        }

        return $count;
    }
}