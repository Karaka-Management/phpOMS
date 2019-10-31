<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Math\Stochastic\Distribution
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Stochastic\Distribution;

/**
 * Bernulli distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class TDistribution
{
    /**
     * Get expected value.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function getMean() : int
    {
        return 0;
    }

    /**
     * Get median.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function getMedian() : int
    {
        return 0;
    }

    /**
     * Get mode.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function getMode() : int
    {
        return 0;
    }

    /**
     * Get skewness.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function getSkewness() : int
    {
        return 0;
    }

    /**
     * Get variance.
     *
     * @param int $nu Degrees of freedom
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVariance(int $nu) : float
    {
        return $nu < 3 ? \PHP_FLOAT_MAX : $nu / ($nu - 2);
    }

    /**
     * Get standard deviation.
     *
     * @param int $nu Degrees of freedom
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getStandardDeviation(int $nu) : float
    {
        return $nu < 3 ? \PHP_FLOAT_MAX : \sqrt(self::getVariance($nu));
    }

    /**
     * Get Ex. kurtosis.
     *
     * @param float $nu Degrees of freedom
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getExKurtosis(float $nu) : float
    {
        return $nu < 5 && $nu > 2 ? \PHP_FLOAT_MAX : 6 / ($nu - 4);
    }
}
