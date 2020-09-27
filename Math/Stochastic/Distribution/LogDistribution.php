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

use phpOMS\Math\Functions\Beta;

/**
 * Log distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class LogDistribution
{
    /**
     * Get probability mass function.
     *
     * @param float $p Value p
     * @param int   $k Value k
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPmf(float $p, int $k) : float
    {
        return -1 / \log(1 - $p) * $p ** $k / $k;
    }

    /**
     * Get cumulative distribution function.
     *
     * @param float $p Value p
     * @param int   $k Value k
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getCdf(float $p, int $k) : float
    {
        // This is a workaround!
        // Actually 0 should be used instead of 0.0001.
        // This is only used because the incomplete beta function doesn't work for p or q = 0
        return 1 + Beta::incompleteBeta($p, $k + 1, 0.0001) / \log(1 - $p);
    }

    /**
     * Get expected value.
     *
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMean(float $p) : float
    {
        return -1 / \log(1 - $p) * $p / (1 - $p);
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
        return 1;
    }

    /**
     * Get variance.
     *
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVariance(float $p) : float
    {
        return -($p ** 2 + $p * \log(1 - $p))
            / ((1 - $p) ** 2 * \log(1 - $p) ** 2);
    }

    /**
     * Get standard deviation.
     *
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getStandardDeviation(float $p) : float
    {
        return \sqrt(self::getVariance($p));
    }

    /**
     * Get moment generating function.
     *
     * @param float $p Value p
     * @param float $t Value t
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMgf(float $p, float $t) : float
    {
        return \log(1 - $p * \exp($t)) / \log(1 - $p);
    }
}
