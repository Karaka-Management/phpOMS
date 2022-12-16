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
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Stochastic\Distribution;

/**
 * Exponential distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ExponentialDistribution
{
    /**
     * Get probability density function.
     *
     * @param float $x      Value x
     * @param float $lambda Lambda
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPdf(float $x, float $lambda) : float
    {
        return $x >= 0 ? $lambda * \exp(-$lambda * $x) : 0;
    }

    /**
     * Get cumulative distribution function.
     *
     * @param float $x      Value x
     * @param float $lambda Lambda
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getCdf(float $x, float $lambda) : float
    {
        return $x >= 0 ? 1 - 1 / \exp($lambda * $x) : 0;
    }

    /**
     * Get mode.
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMode() : float
    {
        return 0;
    }

    /**
     * Get expected value.
     *
     * @param float $lambda Lambda
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMean(float $lambda) : float
    {
        return 1 / $lambda;
    }

    /**
     * Get median.
     *
     * @param float $lambda Lambda
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMedian(float $lambda) : float
    {
        return 1 / $lambda * \log(2);
    }

    /**
     * Get variance.
     *
     * @param float $lambda Lambda
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVariance(float $lambda) : float
    {
        return \pow($lambda, -2);
    }

    /**
     * Get standard deviation.
     *
     * @param float $lambda Lambda
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getStandardDeviation(float $lambda) : float
    {
        return \sqrt(self::getVariance($lambda));
    }

    /**
     * Get moment generating function.
     *
     * @param float $t      Value t
     * @param float $lambda Lambda
     *
     * @return float
     *
     * @throws \OutOfBoundsException
     *
     * @since 1.0.0
     */
    public static function getMgf(float $t, float $lambda) : float
    {
        if ($t >= $lambda) {
            throw new \OutOfBoundsException('Out of bounds');
        }

        return $lambda / ($lambda - $t);
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
        return 2;
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
        return 6;
    }
}
