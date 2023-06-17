<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Statistic\Forecast\Regression
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Statistic\Forecast\Regression;

use phpOMS\Math\Matrix\Exception\InvalidDimensionException;
use phpOMS\Math\Statistic\Average;

/**
 * Regression class.
 *
 * @package phpOMS\Math\Statistic\Forecast\Regression
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class PolynomialRegression
{
    /**
     * Get linear regression based on scatter plot.
     *
     * @param array<int|float> $x Obersved x values
     * @param array<int|float> $y Observed y values
     *
     * @return array [a => ?, b => ?, c => ?]
     *
     * @throws InvalidDimensionException throws this exception if the dimension of both arrays is not equal
     *
     * @since 1.0.0
     */
    public static function getRegression(array $x, array $y) : array
    {
        if (($n = \count($x)) !== \count($y)) {
            throw new InvalidDimensionException(\count($x) . 'x' . \count($y));
        }

        $xm = Average::arithmeticMean($x);
        $ym = Average::arithmeticMean($y);

        $r = \range(0, $n - 1);

        $xTemp = [];
        foreach ($r as $e) {
            $xTemp[] = $e * $e;
        }

        $x2m = Average::arithmeticMean($xTemp);

        $xTemp = [];
        foreach ($r as $e) {
            $xTemp[] = $e * $e * $e;
        }

        $x3m = Average::arithmeticMean($xTemp);

        $xTemp = [];
        foreach ($r as $e) {
            $xTemp[] = $e * $e * $e * $e;
        }

        $x4m = Average::arithmeticMean($xTemp);
        $xym = 0.0;

        for ($i = 0; $i < $n; ++$i) {
            $xym += $x[$i] * $y[$i];
        }

        $xym /= $n;

        $x2ym = 0.0;
        for ($i = 0; $i < $n; ++$i) {
            $x2ym += $x[$i] * $x[$i] * $y[$i];
        }

        $x2ym /= $n;

        $sxx   = $x2m - $xm * $xm;
        $sxy   = $xym - $xm * $ym;
        $sxx2  = $x3m - $xm * $x2m;
        $sx2x2 = $x4m - $x2m * $x2m;
        $sx2y  = $x2ym - $x2m * $ym;

        $b = ($sxy * $sx2x2 - $sx2y * $sxx2) / ($sxx * $sx2x2 - $sxx2 * $sxx2);
        $c = ($sx2y * $sxx - $sxy * $sxx2) / ($sxx * $sx2x2 - $sxx2 * $sxx2);
        $a = $ym - $b * $xm - $c * $x2m;

        return [
            'a' => $a,
            'b' => $b,
            'c' => $c,
        ];
    }
}
