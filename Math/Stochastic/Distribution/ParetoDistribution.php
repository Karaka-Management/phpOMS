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
 * Pareto distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class ParetoDistribution
{
    /**
     * Get probability density function.
     *
     * @param float $x     Value x
     * @param float $xm    Lower bound
     * @param float $alpha Alpha shape
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPdf(float $x, float $xm, float $alpha) : float
    {
        return $alpha * $xm ** $alpha / (\pow($x, $alpha + 1));
    }

    /**
     * Get cumulative distribution function.
     *
     * @param float $x     Value x
     * @param float $xm    Lower bound
     * @param float $alpha Alpha shape
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getCdf(float $x, float $xm, float $alpha) : float
    {
        return 1 - ($xm / $x) ** $alpha;
    }

    /**
     * Get median
     *
     * @param float $xm    Lower bound
     * @param float $alpha Alpha shape
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMedian(float $xm, float $alpha) : float
    {
        return $xm * \pow(2, 1 / $alpha);
    }

    /**
     * Get mode.
     *
     * @param float $xm Lower bound
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMode($xm) : float
    {
        return $xm;
    }

    /**
     * Get variance
     *
     * @param float $xm    Lower bound
     * @param float $alpha Alpha shape
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVariance(float $xm, float $alpha) : float
    {
        if ($alpha < 2) {
            return \PHP_FLOAT_MAX;
        }

        return $xm ** 2 * $alpha / (($alpha - 1) ** 2 * ($alpha - 2));
    }

    /**
     * Get skewness.
     *
     * @param float $alpha Alpha shape
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSkewness(float $alpha) : float
    {
        if ($alpha < 4) {
            return 0.0;
        }

        return 2 * (1 + $alpha) / ($alpha - 3) * \sqrt(($alpha - 2) / $alpha);
    }

    /**
     * Get Ex. kurtosis.
     *
     * @param float $alpha Alpha shape
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getExKurtosis(float $alpha) : float
    {
        if ($alpha < 5) {
            return 0.0;
        }

        return 6 * ($alpha ** 3 + $alpha ** 2 - 6 * $alpha - 2)
            / ($alpha * ($alpha - 3) * ($alpha - 4));
    }

    /**
     * Get entropy.
     *
     * @param float $xm    Lower bound
     * @param float $alpha Alpha shape
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getEntropy(float $xm, float $alpha) : float
    {
        return \log(($xm / $alpha) * \exp(1 + 1 / $alpha));
    }

    /**
     * Get Fisher information.
     *
     * @param float $xm    Lower bound
     * @param float $alpha Alpha shape
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function getFisherInformation(float $xm, float $alpha) : array
    {
        return [
            [$alpha / $xm ** 2, -1 / $xm],
            [-1 / $xm, 1 / ($alpha ** 2)]
        ];
    }
}
