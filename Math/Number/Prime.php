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
 * Well known functions class.
 *
 * @package phpOMS\Math\Number
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Prime
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
     * Is mersenne number?
     *
     * @param int $n Number to test
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isMersenne(int $n) : bool
    {
        $mersenne = \log($n + 1, 2);

        return $mersenne - (int) $mersenne < self::EPSILON;
    }

    /**
     * Get mersenne number
     *
     * @param int $p Power
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function mersenne(int $p) : int
    {
        return (int) (2 ** $p) - 1;
    }

    /**
     * Is prime?
     *
     * @param int $n Number to test
     * @param int $k Accuracy
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function rabinTest(int $n, int $k = 10000) : bool
    {
        if ($n === 2) {
            return true;
        }

        if ($n < 2 || $n % 2 === 0) {
            return false;
        }

        $d = $n - 1;
        $s = 0;

        while ($d % 2 === 0) {
            $d /= 2;
            ++$s;
        }

        for ($i = 0; $i < $k; ++$i) {
            $a = \mt_rand(2, $n - 1);
            $x = \bcpowmod((string) $a, (string) $d, (string) $n);

            if ($x === false) {
                return false;
            }

            if ($x == 1 || $x == $n - 1) {
                continue;
            }

            for ($j = 1; $j < $s; ++$j) {
                if ($x === null) {
                    return false;
                }

                $mul = \bcmul($x, $x);
                /*if ($mul === null) {
                    return false;
                }*/

                $x = \bcmod($mul, (string) $n);
                if ($x == 1 || $x === null) {
                    return false;
                }

                if ($x == $n - 1) {
                    continue 2;
                }
            }

            return false;
        }

        return true;
    }

    /**
     * Create prime numbers
     *
     * @param int $n Primes to generate
     *
     * @return int[]
     *
     * @since 1.0.0
     */
    public static function sieveOfEratosthenes(int $n) : array
    {
        $number = 2;
        $range  = \range(2, $n);
        $primes = \array_combine($range, $range);

        if ($primes === false) {
            return []; // @codeCoverageIgnore
        }

        while ($number * $number < $n) {
            for ($i = $number; $i <= $n; $i += $number) {
                if ($i === $number) {
                    continue;
                }

                unset($primes[$i]);
            }

            $number = \next($primes);
        }

        return \array_values($primes);
    }

    /**
     * Is prime?
     *
     * @param int $n Number to test
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isPrime(int $n) : bool
    {
        $i = 2;

        if ($n === 2) {
            return true;
        }

        $sqrtN = \sqrt($n);
        while ($i <= $sqrtN) {
            if ($n % $i === 0) {
                return false;
            }

            ++$i;
        }

        return true;
    }
}
