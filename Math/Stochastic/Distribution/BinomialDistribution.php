<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Math\Stochastic\Distribution
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Stochastic\Distribution;

use phpOMS\Math\Functions\Functions;

/**
 * Binomial distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class BinomialDistribution
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
     * Get mode.
     *
     * @param int   $n Value n
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMode(int $n, float $p) : float
    {
        return \floor(($n + 1) * $p);
    }

    /**
     * Get moment generating function.
     *
     * @param int   $n Value n
     * @param float $t Value t
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMgf(int $n, float $t, float $p) : float
    {
        return \pow(1 - $p + $p * \exp($t), $n);
    }

    /**
     * Get skewness.
     *
     * @param int   $n Value n
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSkewness(int $n, float $p) : float
    {
        return (1 - 2 * $p) / \sqrt($n * $p * (1 - $p));
    }

    /**
     * Get Fisher information.
     *
     * @param int   $n Value n
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getFisherInformation(int $n, float $p) : float
    {
        return $n / ($p * (1 - $p));
    }

    /**
     * Get Ex. kurtosis.
     *
     * @param int   $n Value n
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getExKurtosis(int $n, float $p) : float
    {
        return (1 - 6 * $p * (1 - $p)) / ($n * $p * (1 - $p));
    }

    /**
     * Get cumulative distribution function.
     *
     * @param int   $n Value n
     * @param int   $x Value x
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getCdf(int $n, int $x, float $p) : float
    {
        $sum = 0.0;

        for ($i = 1; $i < $x; ++$i) {
            $sum += self::getPmf($n, $i, $p);
        }

        return $sum;
    }

    /**
     * Get probability mass function.
     *
     * Formula: C(n, k) * p^k * (1-p)^(n-k)
     *
     * @param int   $n Value n
     * @param int   $k Value k
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPmf(int $n, int $k, float $p) : float
    {
        return Functions::binomialCoefficient($n, $k) * \pow($p, $k) * \pow(1 - $p, $n - $k);
    }

    /**
     * Get median.
     *
     * @param int   $n Value n
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMedian(int $n, float $p) : float
    {
        return \floor($n * $p);
    }

    /**
     * Get expected value.
     *
     * @param int   $n Value n
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMean(int $n, float $p) : float
    {
        return $n * $p;
    }

    /**
     * Get variance.
     *
     * @param int   $n Value n
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVariance(int $n, float $p) : float
    {
        return $n * $p * (1 - $p);
    }

    /**
     * Get standard deviation.
     *
     * @param int   $n Value n
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getStandardDeviation(int $n, float $p) : float
    {
        return \sqrt(self::getVariance($n, $p));
    }
}
