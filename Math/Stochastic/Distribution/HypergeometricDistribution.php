<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Math\Stochastic\Distribution
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Stochastic\Distribution;

use phpOMS\Math\Functions\Functions;

/**
 * Hypergeometric distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class HypergeometricDistribution
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
     * Get probability mass function.
     *
     * @param int $K Successful states in the population
     * @param int $N Population size
     * @param int $k Observed successes
     * @param int $n Number of draws
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPmf(int $K, int $N, int $k, int $n) : float
    {
        return Functions::binomialCoefficient($K, $k) * Functions::binomialCoefficient($N - $K, $n - $k) / Functions::binomialCoefficient($N, $n);
    }

    /**
     * Get expected value.
     *
     * @param int $K Successful states in the population
     * @param int $N Population size
     * @param int $n Number of draws
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMean(int $K, int $N, int $n) : float
    {
        return $n * $K / $N;
    }

    /**
     * Get mode.
     *
     * @param int $K Successful states in the population
     * @param int $N Population size
     * @param int $n Number of draws
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function getMode(int $K, int $N, int $n) : int
    {
        return (int) (($n + 1) * ($K + 1) / ($N + 2));
    }

    /**
     * Get variance.
     *
     * @param int $K Successful states in the population
     * @param int $N Population size
     * @param int $n Number of draws
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVariance(int $K, int $N, int $n) : float
    {
        return $n * $K / $N * ($N - $K) / $N * ($N - $n) / ($N - 1);
    }

    /**
     * Get standard deviation.
     *
     * @param int $K Successful states in the population
     * @param int $N Population size
     * @param int $n Number of draws
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getStandardDeviation(int $K, int $N, int $n) : float
    {
        return \sqrt($n * $K / $N * ($N - $K) / $N * ($N - $n) / ($N - 1));
    }

    /**
     * Get skewness.
     *
     * @param int $K Successful states in the population
     * @param int $N Population size
     * @param int $n Number of draws
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSkewness(int $K, int $N, int $n) : float
    {
        return ($N - 2 * $K) * \sqrt($N - 1) * ($N - 2 * $n)
            / (\sqrt($n * $K * ($N - $K) * ($N - $n)) * ($N - 2));
    }

    /**
     * Get Ex. kurtosis.
     *
     * @param int $K Successful states in the population
     * @param int $N Population size
     * @param int $n Number of draws
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getExKurtosis(int $K, int $N, int $n) : float
    {
        return (($N - 1) * $N ** 2 * ($N * ($N + 1) - 6 * $K * ($N - $K) - 6 * $n * ($N - $n)) + 6 * $n * $K * ($N - $K) * ($N - $n) * (5 * $N - 6))
            / ($n * $K * ($N - $K) * ($N - $n) * ($N - 2) * ($N - 3));
    }

    /**
     * Get cumulative distribution function.
     *
     * @param int $K Successful states in the population
     * @param int $N Population size
     * @param int $k Observed successes
     * @param int $n Number of draws
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getCdf(int $K, int $N, int $k, int $n) : float
    {
        return 1 - Functions::binomialCoefficient($n, $k + 1)
            * Functions::binomialCoefficient($N - $n, $K - $k - 1)
                / Functions::binomialCoefficient($N, $K)
            * Functions::generalizedHypergeometricFunction(
                [1, $k + 1 - $K, $k + 1 - $n],
                [$k + 2, $N + $k + 2 - $K - $n],
                1
            );
    }
}
