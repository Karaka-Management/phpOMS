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
final class Illinois
{
    public const EPSILON = 1e-6;

    public static function bisection(Callable $func, float $a, float $b, $maxIterations = 100) {
        if ($func($a) * $func($b) >= 0) {
            throw new \Exception("Function values at endpoints must have opposite signs.");
        }

        $c = $b;
        $iteration = 0;
        $sign = 1;

        while (($y = \abs($func($c))) > self::EPSILON && $iteration < $maxIterations) {
            $fa = $func($a);
            $fb = $func($b);

            if ($y === 0.0) {
                return $c;
            }

            // @todo: c might be wrong, could be that if and else must be switched
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

        return ($a + $b) / 2;
    }
}
