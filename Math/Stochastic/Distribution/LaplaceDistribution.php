<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Stochastic\Distribution
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Stochastic\Distribution;

/**
 * Laplace distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class LaplaceDistribution
{
    /**
     * Get probability density function.
     *
     * @param float $x  Value x
     * @param float $mu Mean
     * @param float $b  Value b
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPdf(float $x, float $mu, float $b) : float
    {
        return 1 / (2 * $b) * \exp(-\abs($x - $mu) / $b);
    }

    /**
     * Get cumulative distribution function.
     *
     * @param float $x  Value x
     * @param float $mu Mean
     * @param float $b  Value b
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getCdf(float $x, float $mu, float $b) : float
    {
        return $x < $mu ? \exp(($x - $mu) / $b) / 2 : 1 - \exp(-($x - $mu) / $b) / 2;
    }

    /**
     * Get mode.
     *
     * @param float $mu Mean
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMode(float $mu) : float
    {
        return $mu;
    }

    /**
     * Get expected value.
     *
     * @param float $mu Mean
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMean(float $mu) : float
    {
        return $mu;
    }

    /**
     * Get median.
     *
     * @param float $mu Mean
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMedian(float $mu) : float
    {
        return $mu;
    }

    /**
     * Get variance.
     *
     * @param float $b Value b
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVariance(float $b) : float
    {
        return 2 * $b ** 2;
    }

    /**
     * Get standard deviation.
     *
     * @param float $b Value b
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getStandardDeviation(float $b) : float
    {
        return \sqrt(self::getVariance($b));
    }

    /**
     * Get moment generating function.
     *
     * @param float $t  Valute t
     * @param float $mu Mean
     * @param float $b  Value b
     *
     * @return float
     *
     * @throws \OutOfBoundsException
     *
     * @since 1.0.0
     */
    public static function getMgf(float $t, float $mu, float $b) : float
    {
        if (\abs($t) >= 1 / $b) {
            throw new \OutOfBoundsException('Out of bounds');
        }

        return \exp($mu * $t) / (1 - $b ** 2 * $t ** 2);
    }

    /**
     * Get skewness.
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSkewness() : float
    {
        return 0;
    }

    /**
     * Get Ex. kurtosis.
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getExKurtosis() : float
    {
        return 3;
    }
}
