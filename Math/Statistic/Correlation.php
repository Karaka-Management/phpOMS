<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Statistic
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Statistic;

/**
 * Correlation.
 *
 * @package phpOMS\Math\Statistic
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Correlation
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
     * Calculate bravais person correlation coefficient.
     *
     * Example: ([4, 5, 9, 1, 3], [4, 5, 9, 1, 3])
     *
     * @latex \rho_{XY} = \frac{cov(X, Y)}{\sigma_X \sigma_Y}
     *
     * @param array<int|float> $x Values
     * @param array<int|float> $y Values
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function bravaisPersonCorrelationCoefficientPopulation(array $x, array $y) : float
    {
        return MeasureOfDispersion::empiricalCovariance($x, $y) / (MeasureOfDispersion::standardDeviationPopulation($x) * MeasureOfDispersion::standardDeviationPopulation($y));
    }

    /**
     * Calculate bravais person correlation coefficient.
     *
     * Example: ([4, 5, 9, 1, 3], [4, 5, 9, 1, 3])
     *
     * @latex \rho_{XY} = \frac{cov(X, Y)}{\sigma_X \sigma_Y}
     *
     * @param array<int|float> $x Values
     * @param array<int|float> $y Values
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function bravaisPersonCorrelationCoefficientSample(array $x, array $y) : float
    {
        return MeasureOfDispersion::sampleCovariance($x, $y) / (MeasureOfDispersion::standardDeviationSample($x) * MeasureOfDispersion::standardDeviationSample($y));
    }

    /**
     * Get the autocorrelation coefficient (ACF).
     *
     * @param array<int|float> $x Dataset
     * @param int              $k k-th coefficient
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function autocorrelationCoefficient(array $x, int $k = 0) : float
    {
        $squaredMeanDeviation = MeasureOfDispersion::squaredMeanDeviation($x);
        $mean                 = Average::arithmeticMean($x);
        $count                = \count($x);
        $sum                  = 0.0;

        for ($i = $k; $i < $count; ++$i) {
            $sum += ($x[$i] - $mean) * ($x[$i - $k] - $mean);
        }

        return $sum / ($squaredMeanDeviation * $count);
    }

    /**
     * Box Pierce test (portmanteau test).
     *
     * @param float[] $autocorrelations Autocorrelations
     * @param int     $h                Maximum leg considered
     * @param int     $n                Amount of observations
     *
     * @return float
     *
     * @since 1.0.0
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
     * @param float[] $autocorrelations Autocorrelations
     * @param int     $h                Maximum leg considered
     * @param int     $n                Amount of observations
     *
     * @return float
     *
     * @since 1.0.0
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
