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
 * Normal distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class NormalDistribution
{
    /**
     * Chi square table.
     *
     * @var   array<int, array<string, float>>
     * @since 1.0.0
     */
    public const TABLE = [
        '0.50' => 0.67, '0.55' => 0.76, '0.60' => 0.84, '0.65' => 0.93, '0.70' => 1.04, '0.75' => 1.15, '0.80' => 1.28,
        '0.85' => 1.44, '0.90' => 1.64, '0.95' => 1.96, '0.96' => 2.05, '0.97' => 2.17, '0.98' => 2.33, '0.99' => 2.58,
    ];

    /**
     * Get probability density function.
     *
     * @param float $x   Value x
     * @param float $mu  Value mu
     * @param float $sig Sigma
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPdf(float $x, float $mu, float $sig) : float
    {
        return 1 / ($sig * \sqrt(2 * \M_PI)) * \exp(-($x - $mu) ** 2 / (2 * $sig ** 2));
    }

    /**
     * Get probability density function.
     *
     * @param float $x   Value x
     * @param float $mu  Value mu
     * @param float $sig Sigma
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getCdf(float $x, float $mu, float $sig) : float
    {
        return 1 / 2 * (1 + self::erf(($x - $mu) / ($sig * \sqrt(2))));
    }

    /**
     * Error function approximation
     *
     * @param float $x Value x
     *
     * @return float
     *
     * @since 1.0.0
     */
    private static function erf(float $x) : float
    {
        if ($x < 0) {
            return -self::erf(-$x);
        }

        $a = 8 * (\M_PI - 3) / (3 * \M_PI * (4 - \M_PI));

        return \sqrt(1 - \exp(-($x ** 2) * (4 / \M_PI + $a * $x ** 2) / (1 + $a * $x ** 2)));
    }

    /**
     * Get mode.
     *
     * @param float $mu Value mu
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
     * @param float $mu Value mu
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
     * @param float $mu Value mu
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
     * @param float $sig Sigma
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVariance(float $sig) : float
    {
        return $sig ** 2;
    }

    /**
     * Get standard deviation.
     *
     * @param float $sig Sigma
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getStandardDeviation(float $sig) : float
    {
        return $sig;
    }

    /**
     * Get moment generating function.
     *
     * @param float $t   Value t
     * @param float $mu  Value mu
     * @param float $sig Sigma
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMgf(float $t, float $mu, float $sig) : float
    {
        return \exp($mu * $t + ($sig ** 2 * $t ** 2) / 2);
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
     * Get Fisher information.
     *
     * @param float $sig Sigma
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function getFisherInformation(float $sig) : array
    {
        return [[1 / $sig ** 2, 0], [0, 1 / (2 * $sig ** 4)]];
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
        return 0;
    }
}
