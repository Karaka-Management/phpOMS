<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Math\Functions
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Functions;

/**
 * Well known functions and helpers class.
 *
 * @package phpOMS\Math\Functions
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class Functions
{
    /**
     * Epsilon for float comparison.
     *
     * @var float
     * @since 1.0.0
     */
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
     * Calculate gammar function value.
     *
     * Example: (7, 2)
     *
     * @param int $n     Factorial upper bound
     * @param int $start Factorial starting value
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function fact(int $n, int $start = 1) : int
    {
        $fact = 1;

        for ($i = $start; $i < $n + 1; ++$i) {
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
     * @param int $n n
     * @param int $k k
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function binomialCoefficient(int $n, int $k) : int
    {
        $max = \max([$k, $n - $k]);
        $min = \min([$k, $n - $k]);

        $fact  = 1;
        $range = \array_reverse(\range(1, $min));

        for ($i = $max + 1; $i < $n + 1; ++$i) {
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

        return (int) ($fact / $fact2);
    }

    /**
     * Calculate ackermann function.
     *
     * @param int $m m
     * @param int $n n
     *
     * @return int
     *
     * @since 1.0.0
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
     * Calculate inverse modular.
     *
     * @param int $a a
     * @param int $n Modulo
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function invMod(int $a, int $n) : int
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
     * @param int $a a
     * @param int $b b
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function mod(int $a, int $b) : int
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
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isOdd(int $a) : bool
    {
        return (bool) ($a & 1);
    }

    /**
     * Check if value is even.
     *
     * @param int $a Value to test
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isEven(int $a) : bool
    {
        return !((bool) ($a & 1));
    }

    /**
     * Gets the relative position on a circular construct.
     *
     * @example The relative fiscal month (August) in a company where the fiscal year starts in July.
     * @example 2 = getRelativeDegree(8, 12, 7);
     *
     * @param int $value  Value to get degree
     * @param int $length Circle size
     * @param int $start  Start value
     *
     * @return int Lowest value is 0 and highest value is length - 1
     *
     * @since 1.0.0
     */
    public static function getRelativeDegree(int $value, int $length, int $start = 0) : int
    {
        return \abs(self::mod($value - $start, $length));
    }

    /**
     * Calculate the value of the error function (gauss error function)
     *
     * @param float $value Value
     *
     * @return float
     *
     * @see Sylvain Chevillard; HAL Id: ensl-00356709
     * @see https://hal-ens-lyon.archives-ouvertes.fr/ensl-00356709v3
     *
     * @since 1.0.0
     */
    public static function getErf(float $value) : float
    {
        if (\abs($value) > 2.2) {
            return 1 - self::getErfc($value);
        }

        $valueSquared = $value * $value;
        $sum          = $value;
        $term         = $value;
        $i            = 1;

        do {
            $term *= $valueSquared / $i;
            $sum  -= $term / (2 * $i + 1);

            ++$i;

            $term *= $valueSquared / $i;
            $sum  += $term / (2 * $i + 1);

            ++$i;
        } while ($sum !== 0.0 && \abs($term / $sum) > self::EPSILON);

        return 2 / \sqrt(\M_PI) * $sum;
    }

    /**
     * Calculate the value of the complementary error fanction
     *
     * @param float $value Value
     *
     * @return float
     *
     * @see Sylvain Chevillard; HAL Id: ensl-00356709
     * @see https://hal-ens-lyon.archives-ouvertes.fr/ensl-00356709v3
     *
     * @since 1.0.0
     */
    public static function getErfc(float $value) : float
    {
        if (\abs($value) <= 2.2) {
            return 1 - self::getErf($value);
        }

        if ($value < 0.0) {
            return 2 - self::getErfc(-$value);
        }

        $a  = $n = 1;
        $b  = $c = $value;
        $d  = ($value * $value) + 0.5;
        $q1 = $q2 = $b / $d;
        $t  = 0;

        do {
            $t  = $a * $n + $b * $value;
            $a  = $b;
            $b  = $t;
            $t  = $c * $n + $d * $value;
            $c  = $d;
            $d  = $t;
            $n += 0.5;
            $q1 = $q2;
            $q2 = $b / $d;
        } while (\abs($q1 - $q2) / $q2 > self::EPSILON);

        return 1 / \sqrt(\M_PI) * \exp(-$value * $value) * $q2;
    }

    /**
     * Generalized hypergeometric function.
     *
     * pFq(a1, ..., ap; b1, ..., bq; z)
     *
     * @param array<int, float|int> $a Array of values
     * @param array<int, float|int> $b Array of values
     * @param float                 $z Z
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function generalizedHypergeometricFunction(array $a, array $b, float $z) : float
    {
        $sum   = 0.0;
        $aProd = \array_fill(0, 20, []);
        $bProd = \array_fill(0, 20, []);

        for ($n = 0; $n < 20; ++$n) {
            foreach ($a as $key => $value) {
                if ($n === 0) {
                    $aProd[$n][$key] = 1;
                } else {
                    $aProd[$n][$key] = $aProd[$n - 1][$key] * ($value + $n - 1);
                }
            }

            foreach ($b as $key => $value) {
                if ($n === 0) {
                    $bProd[$n][$key] = 1;
                } else {
                    $bProd[$n][$key] = $bProd[$n - 1][$key] * ($value + $n - 1);
                }
            }

            $temp = \array_product($aProd[$n]) / \array_product($bProd[$n]);
            $sum += $temp * $z ** $n / self::fact($n);
        }

        return $sum;
    }
}
