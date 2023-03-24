<?php
/**
 * Karaka
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
 * Pareto distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ParetoDistribution
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
     * Get expected value.
     *
     * @param float $xm    Lower bound
     * @param float $alpha Alpha shape
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMean(float $xm, float $alpha) : float
    {
        return $alpha > 1 ? $alpha * $xm / ($alpha - 1) : \PHP_FLOAT_MAX;
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
    public static function getMode(float $xm) : float
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
        return $alpha < 3 ? \PHP_FLOAT_MAX : $xm ** 2 * $alpha / (($alpha - 1) ** 2 * ($alpha - 2));
    }

    /**
     * Get standard deviation
     *
     * @param float $xm    Lower bound
     * @param float $alpha Alpha shape
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getStandardDeviation(float $xm, float $alpha) : float
    {
        return \sqrt(self::getVariance($xm, $alpha));
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
        return $alpha < 4 ? 0.0 : 2 * (1 + $alpha) / ($alpha - 3) * \sqrt(($alpha - 2) / $alpha);
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
            [-1 / $xm, 1 / ($alpha ** 2)],
        ];
    }
}
