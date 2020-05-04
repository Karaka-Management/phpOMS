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
 * Log-normal distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class LogNormalDistribution
{
    /**
     * Get probability density function.
     *
     * @param float $x     Value x
     * @param float $mu    Mu
     * @param float $sigma Sigma
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPdf(float $x, float $mu, float $sigma) : float
    {
        return 1 / ($x * $sigma * \sqrt(2 * \M_PI))
            * \exp(-(\log($x) - $mu) ** 2 / (2 * $sigma ** 2));
    }

    /**
     * Get expected value.
     *
     * @param float $mu    Mu
     * @param float $sigma Sigma = standard deviation
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMean(float $mu, float $sigma) : float
    {
        return \exp($mu + $sigma ** 2 / 2);
    }

    /**
     * Get median.
     *
     * @param float $mu Mu
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMedian(float $mu) : float
    {
        return \exp($mu);
    }

    /**
     * Get mode.
     *
     * @param float $mu    Mu
     * @param float $sigma Sigma
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMode(float $mu, float $sigma) : float
    {
        return \exp($mu - $sigma ** 2);
    }

    /**
     * Get variance.
     *
     * @param float $mu    Mu
     * @param float $sigma Sigma
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVariance(float $mu, float $sigma) : float
    {
        return (\exp($sigma ** 2) - 1) * \exp(2 * $mu + $sigma ** 2);
    }

    /**
     * Get standard deviation.
     *
     * @param float $mu    Mu
     * @param float $sigma Sigma
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getStandardDeviation(float $mu, float $sigma) : float
    {
        return \sqrt(self::getVariance($mu, $sigma));
    }

    /**
     * Get skewness.
     *
     * @param float $sigma Sigma
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSkewness(float $sigma) : float
    {
        return (\exp($sigma ** 2) + 2) * \sqrt(\exp($sigma ** 2) - 1);
    }

    /**
     * Get Ex. kurtosis.
     *
     * @param float $sigma Sigma
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getExKurtosis(float $sigma) : float
    {
        return \exp(4 * $sigma ** 2) + 2 * \exp(3 * $sigma ** 2) + 3 * \exp(2 * $sigma ** 2) - 6;
    }

    /**
     * Get entrpoy.
     *
     * @param float $mu    Mu
     * @param float $sigma Sigma
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getEntrpoy(float $mu, float $sigma) : float
    {
        return \log($sigma * \exp($mu + 1 / 2) * \sqrt(2 * \M_1_PI), 2);
    }

    /**
     * Get Fisher information.
     *
     * @param float $sigma Sigma
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function getFisherInformation(float $sigma) : array
    {
        return [
            [1 / ($sigma ** 2), 0],
            [0, 1 / (2 * $sigma ** 2)],
        ];
    }

    /**
     * Log-Normal-Distribution
     *
     * @param float $value             Value
     * @param float $mean              Mean
     * @param float $standardDeviation Standard deviation
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function dist(float $value, float $mean, float $standardDeviation) : float
    {
        return NormalDistribution::dist((\log($value) - $mean) / $standardDeviation, 0.0, 1.0, true);
    }
}
