<?php

namespace phpOMS\Math\Statistic\Forecast\Regression;

use phpOMS\Math\Statistic\Average;
use phpOMS\Math\Statistic\Forecast\ForecastIntervalMultiplier;
use phpOMS\Math\Statistic\MeasureOfDispersion;

abstract class RegressionAbstract
{
    /**
     * Get linear regression based on scatter plot.
     *
     * y = b0 + b1 * x
     *
     * @param array $x Obersved x values
     * @param array $y Observed y values
     *
     * @return array [b0 => ?, b1 => ?]
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getRegression(array $x, array $y) : array
    {
        if (count($x) != count($y)) {
            throw new \Exception('Dimension');
        }

        $b1 = self::getBeta1($x, $y);

        return ['b0' => self::getBeta0($x, $y, $b1), 'b1' => $b1];
    }

    /**
     * Standard error of the regression.
     *
     * Used in order to evaluate the performance of the linear regression
     *
     * @param array $errors Errors (e = y - y_forecasted)
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getStandardErrorOfRegression(array $errors) : float
    {
        $count = count($errors);
        $sum   = 0.0;

        for ($i = 0; $i < $count; $i++) {
            $sum += $errors[$i] ** 2;
        }

        // todo: could this be - 1 depending on the different definitions?!
        return sqrt(1 / ($count - 2) * $sum);
    }

    /**
     * Get predictional interval for linear regression.
     *
     * @param float $forecasted Forecasted y value
     * @param array $x          observex x values
     * @param array $errors     Errors for y values (y - y_forecasted)
     * @param float $multiplier Multiplier for interval
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getPredictionInterval(float $forecasted, array $x, array $errors, float $multiplier = ForecastIntervalMultiplier::P_95) : array
    {
        $count = count($x);
        $meanX = Average::arithmeticMean($x);
        $sum   = 0.0;

        for ($i = 0; $i < $count; $i++) {
            $sum += ($x[$i] - $meanX) ** 2;
        }

        $interval = $multiplier * self::getStandardErrorOfRegression($errors) * sqrt(1 + 1 / $count + $sum / (($count - 1) * MeasureOfDispersion::standardDeviation($x) ** 2));

        return [$forecasted - $interval, $forecasted + $interval];
    }

    /**
     * Get linear regression parameter beta 1.
     *
     * @param array $x Obersved x values
     * @param array $y Observed y values
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function getBeta1(array $x, array $y) : float
    {
        $count = count($x);
        $meanX = Average::arithmeticMean($x);
        $meanY = Average::arithmeticMean($y);

        $sum1 = 0;
        $sum2 = 0;

        for ($i = 0; $i < $count; $i++) {
            $sum1 += ($y[$i] - $meanY) * ($x[$i] - $meanX);
            $sum2 += ($x[$i] - $meanX) ** 2;
        }

        return $sum1 / $sum2;
    }

    /**
     * Get linear regression parameter beta 0.
     *
     * @param array $x  Obersved x values
     * @param array $y  Observed y values
     * @param float $b1 Beta 1
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function getBeta0(array $x, array $y, float $b1) : float
    {
        return Average::arithmeticMean($y) - $b1 * Average::arithmeticMean($x);
    }

    abstract public static function getSlope(float $b1, float $y, float $x) : float;

    abstract public static function getElasticity(float $b1, float $y, float $x): float;
}