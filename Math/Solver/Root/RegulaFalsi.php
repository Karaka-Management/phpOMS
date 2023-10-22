<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Solver\Root
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Solver\Root;

/**
 * Find the root of a function.
 *
 * @package phpOMS\Math\Solver\Root
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class RegulaFalsi
{
    /**
     * Epsilon for float comparison.
     *
     * @var float
     * @since 1.0.0
     */
    public const EPSILON = 1e-6;

    /**
     * Perform bisection to find the root of a function
     *
     * Iteratively searches for root between two points on the x-axis
     *
     * @param Callable $func          Function defintion
     * @param float    $a             Start value
     * @param float    $b             End value
     * @param int      $maxIterations Maximum amount of iterations
     *
     * @throws \Exception
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function root(callable $func, float $a, float $b, int $maxIterations = 100) : float
    {
        if ($func($a) * $func($b) >= 0) {
            throw new \Exception("Function values at endpoints must have opposite signs.");
        }

        $c         = $b;
        $iteration = 0;

        while (($y = \abs($func($c))) > self::EPSILON && $iteration < $maxIterations) {
            $fa = $func($a);
            $fb = $func($b);

            $c = ($a * $fb - $b * $fa) / ($fb - $fa);

            if ($y === 0.0) {
                return $c;
            }

            if ($y * $fa < 0) {
                $b = $c;
            } else {
                $a = $c;
            }

            ++$iteration;
        }

        return $c;
    }
}
