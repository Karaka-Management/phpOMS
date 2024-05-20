<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Math\Stochastic\Distribution
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Stochastic\Distribution;

/**
 * Geometric distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class GeometricDistribution
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

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
        return \pow(1 - $p, $k - 1) * $p;
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
        return 1 - \pow(1 - $p, $k);
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
        return 1 / $p;
    }

    /**
     * Get median.
     *
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMedian(float $p) : float
    {
        return \ceil(-1 / (\log(1 - $p, 2)));
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
        return (1 - $p) / $p ** 2;
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
        return $t < -\log(1 - $p)
            ? $p * \exp($t) / (1 - (1 - $p) * \exp($t))
            : $p / (1 - (1 - $p) * \exp($t));
    }

    /**
     * Get skewness.
     *
     * @param float $lambda Lambda
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSkewness(float $lambda) : float
    {
        return (2 - $lambda) / \sqrt(1 - $lambda);
    }

    /**
     * Get Ex. kurtosis.
     *
     * @param float $lambda Lambda
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getExKurtosis(float $lambda) : float
    {
        return 6 + $lambda ** 2 / (1 - $lambda);
    }
}
