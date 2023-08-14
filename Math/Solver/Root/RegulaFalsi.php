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
final class RegulaFalsi
{
    public const EPSILON = 1e-6;

    public static function bisection(Callable $func, float $a, float $b, $maxIterations = 100) {
        if ($func($a) * $func($b) >= 0) {
            throw new \Exception("Function values at endpoints must have opposite signs.");
        }

        $c = $b;
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

        return ($a + $b) / 2;
    }
}
