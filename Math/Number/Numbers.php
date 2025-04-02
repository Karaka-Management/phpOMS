<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Math\Number
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Number;

/**
 * Numbers class.
 *
 * @package phpOMS\Math\Number
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Numbers
{
    public const SFLOAT = 1.175494351E-38;

    public const EPSILON = 4.88e-04;

    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Is perfect number?
     *
     * @param int $n Number to test
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isPerfect(int $n) : bool
    {
        $sum = 0;

        for ($i = 1; $i < $n; ++$i) {
            if ($n % $i == 0) {
                $sum += $i;
            }
        }

        return $sum === $n;
    }

    /**
     * Is self describing number?
     *
     * @param int $n Number to test
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isSelfdescribing(int $n) : bool
    {
        $n     = (string) $n;
        $split = \str_split($n);

        foreach ($split as $place => $value) {
            if (\substr_count($n, (string) $place) != $value) {
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
     * @since 1.0.0
     */
    public static function isSquare(int $n) : bool
    {
        return \abs(((int) \sqrt($n)) * ((int) \sqrt($n)) - $n) < self::EPSILON;
    }

    /**
     * Count trailing zeros
     *
     * @param int $n Number to test
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function countTrailingZeros(int $n) : int
    {
        $count = 0;
        while ($n !== 0) {
            if (($n & 1) === 1) {
                break;
            }

            ++$count;
            $n >>= 1;
        }

        return $count;
    }

    /**
     * Remap numbers between 0 and X to 0 and 100
     *
     * @param int   $number Number to remap
     * @param int   $max    Max possible number
     * @param float $exp    Exponential modifier
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function remapRangeExponentially(int $number, int $max, float $exp = 1.0) : float
    {
        if ($number > $max) {
            $number = $max;
        }

        $exponent = ($number / $max) * $exp;
        
        return (\exp($exponent) - 1) / (\exp($exp) - 1) * 100;
    }

    /**
     * Remap numbers between 0 and X to 0 and 100
     *
     * @param int $number Number to remap
     * @param int $max    Max possible number
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function remapRangeLog(int $number, int $max) : float
    {
        if ($number > $max) {
            $number = $max;
        }

        return (\log($number + 1) / \log($max + 1)) * 100;
    }
}
