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
final class Illinois
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
     * Perform bisection to find the root of a function
     *
     * Iteratively searches for root between two points on the x-axis
     *
     * @param Callable $func          Function definition
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
        $sign      = 1;

        while (($y = \abs($func($c))) > self::EPSILON && $iteration < $maxIterations) {
            $fa = $func($a);
            $fb = $func($b);

            if ($y === 0.0) {
                return $c;
            }

            // @todo c might be wrong, could be that if and else must be switched
            // @see https://en.wikipedia.org/wiki/Regula_falsi#The_Illinois_algorithm
            if ($y * $fa < 0) {
                $c = $sign === (int) ($y >= 0)
                    ? (0.5 * $a * $fb - $b * $fa) / (0.5 * $fb - $fa)
                    : ($a * $fb - $b * $fa) / ($fb - $fa);

                $b = $c;
            } else {
                $c = $sign === (int) ($y >= 0)
                    ? ($a * $fb - 0.5 * $b * $fa) / ($fb - 0.5 * $fa)
                    : ($a * $fb - $b * $fa) / ($fb - $fa);

                $a = $c;
            }

            $sign = (int) ($y > 0);

            ++$iteration;
        }

        return $c;
    }
}
