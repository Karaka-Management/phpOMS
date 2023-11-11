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
 * Regression abstract class.
 *
 * @package phpOMS\Math\Statistic\Forecast\Regression
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class RegressionAbstract
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
     * Get linear regression based on scatter plot.
     *
     * @latex y = b_{0} + b_{1} \cdot x
     *
     * @param array<int|float> $x Obersved x values
     * @param array<int|float> $y Observed y values
     *
     * @return array [b0 => ?, b1 => ?]
     *
     * @throws InvalidDimensionException throws this exception if the dimension of both arrays is not equal
     *
     * @since 1.0.0
     */
    public static function getRegression(array $x, array $y) : array
    {
        if (\count($x) !== \count($y)) {
            throw new InvalidDimensionException(\count($x) . 'x' . \count($y));
        }

        $b1 = self::getBeta1($x, $y);

        return ['b0' => self::getBeta0($x, $y, $b1), 'b1' => $b1];
    }

    /**
     * Standard error of the regression for a population
     *
     * Used in order to evaluate the performance of the linear regression
     *
     * @latex s_{e} = \sqrt{\frac{1}{N - 2}\sum_{i = 1}^{N} e_{i}^{2}}
     *
     * @param array<int|float> $errors Errors (e = y - y_forecasted)
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getStandardErrorOfRegressionPopulation(array $errors) : float
    {
        $count = \count($errors);
        $sum   = 0.0;

        for ($i = 0; $i < $count; ++$i) {
            $sum += $errors[$i] ** 2;
        }

        return \sqrt($sum / $count);
    }

    /**
     * Standard error of the regression for a sample
     *
     * Used in order to evaluate the performance of the linear regression
     *
     * @latex s_{e} = \sqrt{\frac{1}{N - 2}\sum_{i = 1}^{N} e_{i}^{2}}
     *
     * @param array<int|float> $errors Errors (e = y - y_forecasted)
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getStandardErrorOfRegressionSample(array $errors) : float
    {
        $count = \count($errors);
        $sum   = 0.0;

        for ($i = 0; $i < $count; ++$i) {
            $sum += $errors[$i] ** 2;
        }

        return \sqrt($sum / ($count - 2));
    }

    /**
     * Get predictional interval for linear regression.
     *
     * @latex
     *
     * @param float            $fX         Forecasted at x value
     * @param float            $fY         Forecasted y value
     * @param array<int|float> $x          observex x values
     * @param float            $mse        Errors for y values (y - y_forecasted)
     * @param float            $multiplier Multiplier for interval
     *
     * @return array<int|float>
     *
     * @since 1.0.0
     */
    public static function getPredictionIntervalMSE(float $fX, float $fY, array $x, float $mse, float $multiplier = 1.96) : array
    {
        $count = \count($x);
        $meanX = Average::arithmeticMean($x);
        $sum   = 0.0;

        for ($i = 0; $i < $count; ++$i) {
            $sum += ($x[$i] - $meanX) ** 2;
        }

        $interval = $multiplier * \sqrt($mse + $mse / $count + $mse * ($fX - $meanX) ** 2 / $sum);

        return [$fY - $interval, $fY + $interval];
    }

    /**
     * Get linear regression parameter beta 1.
     *
     * @latex \beta_{1} = \frac{\sum_{i=1}^{N} \left(y_{i} - \bar{y}\right)\left(x_{i} - \bar{x}\right)}{\sum_{i=1}^{N} \left(x_{i} - \bar{x}\right)^{2}}
     *
     * @param array<int|float> $x Obersved x values
     * @param array<int|float> $y Observed y values
     *
     * @return float
     *
     * @since 1.0.0
     */
    private static function getBeta1(array $x, array $y) : float
    {
        $count = \count($x);
        $meanX = Average::arithmeticMean($x);
        $meanY = Average::arithmeticMean($y);

        $sum1 = 0;
        $sum2 = 0;

        for ($i = 0; $i < $count; ++$i) {
            $sum1 += ($y[$i] - $meanY) * ($x[$i] - $meanX);
            $sum2 += ($x[$i] - $meanX) ** 2;
        }

        return $sum1 / $sum2;
    }

    /**
     * Get linear regression parameter beta 0.
     *
     * @latex \beta_{0} = \bar{x} - b_{1} \cdot \bar{x}
     *
     * @param array<int|float> $x  Obersved x values
     * @param array<int|float> $y  Observed y values
     * @param float            $b1 Beta 1
     *
     * @return float
     *
     * @since 1.0.0
     */
    private static function getBeta0(array $x, array $y, float $b1) : float
    {
        return Average::arithmeticMean($y) - $b1 * Average::arithmeticMean($x);
    }

    /**
     * Get slope
     *
     * @param float $b1 Beta 1
     * @param float $x  Obersved x values
     * @param float $y  Observed y values
     *
     * @return float
     *
     * @since 1.0.0
     */
    abstract public static function getSlope(float $b1, float $x, float $y) : float;

    /**
     * Get elasticity
     *
     * @param float $b1 Beta 1
     * @param float $x  Obersved x values
     * @param float $y  Observed y values
     *
     * @return float
     *
     * @since 1.0.0
     */
    abstract public static function getElasticity(float $b1, float $x, float $y) : float;
}
