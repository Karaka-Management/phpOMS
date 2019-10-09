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
     * @return float
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
        if ($nu < 2) {
            return \PHP_FLOAT_MAX;
        }

        return $nu / ($nu - 2);
    }
}
