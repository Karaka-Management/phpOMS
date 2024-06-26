<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Math\Number
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Number;

/**
 * Integer class
 *
 * @package phpOMS\Math\Number
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Integer
{
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
     * Is integer.
     *
     * @param mixed $value Value to test
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isInteger(mixed $value) : bool
    {
        return \is_int($value);
    }

    /**
     * Trial factorization.
     *
     * @param int $value Integer to factorize
     *
     * @return array<int|float>
     *
     * @since 1.0.0
     */
    public static function trialFactorization(int $value) : array
    {
        if ($value < 2) {
            return [];
        }

        $factors = [];
        $primes  = Prime::sieveOfEratosthenes((int) $value ** 0.5);

        foreach ($primes as $prime) {
            if ($prime * $prime > $value) {
                break;
            }

            while ($value % $prime === 0) {
                $factors[] = $prime;
                $value /= $prime;
            }
        }

        if ($value > 1) {
            $factors[] = $value;
        }

        return $factors;
    }

    /**
     * Pollard's Rho.
     *
     * Integer factorization algorithm
     *
     * @param int $n         Integer to factorize
     * @param int $x         Used for g(x) = (x^2 + 1) mod n
     * @param int $factor    Period for repetition
     * @param int $cycleSize Cycle size
     * @param int $y         Fixed value for g(x) = g(y) mod p
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function pollardsRho(int $n, int $x = 2, int $factor = 1, int $cycleSize = 2, int $y = 2) : int
    {
        while ($factor === 1) {
            for ($i = 1; $i < $cycleSize && $factor <= 1; ++$i) {
                $x      = ($x * $x + 1) % $n;
                $factor = self::greatestCommonDivisor($x - $y, $n);
            }

            $cycleSize *= 2;
            $y = $x;
        }

        return $factor;
    }

    /**
     * Greatest common diviser.
     *
     * @param int $n Number one
     * @param int $m Number two
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function greatestCommonDivisor(int $n, int $m) : int
    {
        $n = \abs($n);
        $m = \abs($m);

        while ($n !== $m) {
            if ($n > $m) {
                $n -= $m;
            } else {
                $m -= $n;
            }
        }

        return $m;
    }

    /**
     * Fermat factorization of odd integers.
     *
     * @param int $value Integer to factorize
     * @param int $limit Max amount of iterations
     *
     * @return int[]
     *
     * @throws \InvalidArgumentException This exception is thrown if the value is not odd
     *
     * @since 1.0.0
     */
    public static function fermatFactor(int $value, int $limit = 1000000) : array
    {
        if (($value % 2) === 0) {
            throw new \InvalidArgumentException('Only odd integers are allowed');
        }

        $a  = (int) \ceil(\sqrt($value));
        $b2 = ($a * $a - $value);
        $i  = 1;

        while (!Numbers::isSquare($b2) && $i < $limit) {
            ++$i;
            ++$a;
            $b2 = ($a * $a - $value);
        }

        return [(int) \round($a - \sqrt($b2)), (int) \round($a + \sqrt($b2))];
    }
}
