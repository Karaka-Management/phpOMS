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
 * Basic math function evaluation.
 *
 * @package phpOMS\Math\Solver\Root
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @todo: implement
 */
final class Bisection
{
    public const EPSILON = 1e-6;

    public static function bisection(Callable $func, float $a, float $b, $maxIterations = 100) {
        if ($func($a) * $func($b) >= 0) {
            throw new \Exception("Function values at endpoints must have opposite signs.");
        }

        $iteration = 0;
        while (($b - $a) / 2 > self::EPSILON && $iteration < $maxIterations) {
            $c = ($a + $b) / 2;

            $y = $func($c);

            if ($y === 0.0) {
                return $c;
            }

            if ($y * $func($a) < 0) {
                $b = $c;
            } else {
                $a = $c;
            }

            ++$iteration;
        }

        return ($a + $b) / 2;
    }
}
