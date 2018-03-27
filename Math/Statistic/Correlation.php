<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Math\Statistic
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Math\Statistic;

/**
 * Correlation.
 *
 * @package    phpOMS\Math\Statistic
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class Correlation
{

    /**
     * Calculage bravais person correlation coefficient.
     *
     * Example: ([4, 5, 9, 1, 3], [4, 5, 9, 1, 3])
     *
     * @param array $x Values
     * @param array $y Values
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function bravaisPersonCorrelationCoefficient(array $x, array $y) : float
    {
        return MeasureOfDispersion::empiricalCovariance($x, $y) / (MeasureOfDispersion::standardDeviation($x) * MeasureOfDispersion::standardDeviation($y));
    }

    /**
     * Get the autocorrelation coefficient (ACF).
     *
     * @param array $x Dataset
     * @param int   $k k-th coefficient
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function autocorrelationCoefficient(array $x, int $k = 0) : float
    {
        $squaredMeanDeviation = MeasureOfDispersion::squaredMeanDeviation($x);
        $mean                 = Average::arithmeticMean($x);
        $count                = count($x);
        $sum                  = 0.0;

        for ($i = $k; $i < $count; ++$i) {
            $sum += ($x[$i] - $mean) * ($x[$i - $k] - $mean);
        }

        return $sum / ($squaredMeanDeviation * $count);
    }

    /**
     * Box Pierce test (portmanteau test).
     *
     * @param array $autocorrelations Autocorrelations
     * @param int   $h                Maximum leg considered
     * @param int   $n                Amount of observations
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function boxPierceTest(array $autocorrelations, int $h, int $n) : float
    {
        $sum = 0;
        for ($i = 0; $i < $h; ++$i) {
            $sum += $autocorrelations[$i] ** 2;
        }

        return $n * $sum;
    }

    /**
     * Ljung Box test (portmanteau test).
     *
     * @param array $autocorrelations Autocorrelations
     * @param int   $h                Maximum leg considered
     * @param int   $n                Amount of observations
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function ljungBoxTest(array $autocorrelations, int $h, int $n) : float
    {
        $sum = 0;

        for ($i = 0; $i < $h; ++$i) {
            $sum += 1 / ($n - ($i + 1)) * $autocorrelations[$i] ** 2;
        }

        return $n * ($n + 2) * $sum;
    }
}
