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

use phpOMS\Math\Number\Numbers;

/**
 * Well known functions class.
 *
 * @package phpOMS\Math\Functions
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class Fibonacci
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
     * Is Fibonacci number.
     *
     * @param int $n Integer
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isFibonacci(int $n) : bool
    {
        return Numbers::isSquare(5 * $n ** 2 + 4) || Numbers::isSquare(5 * $n ** 2 - 4);
    }

    /**
     * Get n-th Fibonacci number.
     *
     * @param int $n     n-th number
     * @param int $start Start value
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function fib(int $n, int $start = 1) : int
    {
        if ($n < 3) {
            return $start;
        }

        $old1 = $start;
        $old2 = $start;
        $fib  = 0;

        for ($i = 2; $i < $n; ++$i) {
            $fib  = $old1 + $old2;
            $old1 = $old2;
            $old2 = $fib;
        }

        return $fib;
    }

    /**
     * Calculate n-th Fibonacci with binets formula.
     *
     * @param int $n n-th number
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function binet(int $n) : int
    {
        return (int) (((1 + \sqrt(5)) ** $n - (1 - \sqrt(5)) ** $n) / (2 ** $n * \sqrt(5)));
    }
}
