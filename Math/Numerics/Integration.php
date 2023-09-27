<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Numerics
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Numerics;

/**
 * Numerical integration.
 *
 * @package phpOMS\Math\Numerics
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Integration
{
    /**
     * Integrate function by using rectangles from the left side
     *
     * @param float    $from Start interval
     * @param float    $to   End interval
     * @param float    $n    Steps
     * @param Callable $func Function to integrate
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function intLeftRect(float $from, float $to, float $n, callable $func) : float
    {
        $h   = ($to - $from) / $n;
        $sum = 0.0;

        for ($x = $from; $x <= ($to - $h); $x += $h) {
            $sum += $func($x);
        }

        return $h * $sum;
    }

    /**
     * Integrate function by using rectangles from the right side
     *
     * @param float    $from Start interval
     * @param float    $to   End interval
     * @param float    $n    Steps
     * @param Callable $func Function to integrate
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function intRightRect(float $from, float $to, float $n, callable $func) : float
    {
        $h   = ($to - $from) / $n;
        $sum = 0.0;

        for ($x = $from; $x <= ($to - $h); $x += $h) {
            $sum += $func($x + $h);
        }

        return $h * $sum;
    }

    /**
     * Integrate function by using rectangles from a moving center point
     *
     * @param float    $from Start interval
     * @param float    $to   End interval
     * @param float    $n    Steps
     * @param Callable $func Function to integrate
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function intMiddleRect(float $from, float $to, float $n, callable $func) : float
    {
        $h   = ($to - $from) / $n;
        $sum = 0.0;

        for ($x = $from; $x <= ($to - $h); $x += $h) {
            $sum += $func($x + $h / 2.0);
        }

        return $h * $sum;
    }

    /**
     * Integrate function by using trapezium
     *
     * @param float    $from Start interval
     * @param float    $to   End interval
     * @param float    $n    Steps
     * @param Callable $func Function to integrate
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function intTrapezium(float $from, float $to, float $n, callable $func) : float
    {
        $h   = ($to - $from) / $n;
        $sum = $func($from) + $func($to);

        for ($i = 1; $i < $n; ++$i) {
            $sum += 2 * $func($from + $i * $h);
        }

        return $h * $sum / 2.0;
    }

    /**
     * Integrate by using the simpson rule
     *
     * @param float    $from Start interval
     * @param float    $to   End interval
     * @param float    $n    Steps
     * @param Callable $func Function to integrate
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function intSimpson(float $from, float $to, float $n, callable $func) : float
    {
        $h    = ($to - $from) / $n;
        $sum1 = 0.0;
        $sum2 = 0.0;

        for ($i = 0; $i < $n; ++$i) {
            $sum1 += $func($from + $h * $i + $h / 2.0);
        }

        for ($i = 1; $i < $n; ++$i) {
            $sum2 += $func($from + $h * $i);
        }

        return $h / 6.0 * ($func($from) + $func($to) + 4.0 * $sum1 + 2.0 * $sum2);
    }
}
