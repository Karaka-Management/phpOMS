<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Stochastic\Distribution
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Stochastic\Distribution;

/**
 * Logistic distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class LogisticDistribution
{
    /**
     * Get probability density function.
     *
     * @param float $x  Value x
     * @param float $mu Mean
     * @param float $s  s scale
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPdf(float $x, float $mu, float $s) : float
    {
        return \exp(-($x - $mu) / $s)
            / ($s * (1 + \exp(-($x - $mu) / $s)) ** 2);
    }

    /**
     * Get cumulative distribution function.
     *
     * @param float $x  Value x
     * @param float $mu Mean
     * @param float $s  s scale
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getCdf(float $x, float $mu, float $s) : float
    {
        return 1 / (1 + \exp(-($x - $mu) / $s));
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
     * @param float $s s scale
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVariance(float $s) : float
    {
        return $s ** 2 * \M_PI ** 2 / 3;
    }

    /**
     * Get standard deviation.
     *
     * @param float $s s scale
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getStandardDeviation(float $s) : float
    {
        return \sqrt(self::getVariance($s));
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
     * Get skewness.
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getExKurtosis() : float
    {
        return 6 / 5;
    }

    /**
     * Get entropy.
     *
     * @param float $s s scale
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getEntropy(float $s) : float
    {
        return \log($s) + 2;
    }
}
