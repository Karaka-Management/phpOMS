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

/**
 * Bernulli distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class BernoulliDistribution
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
     * @throws \InvalidArgumentException
     *
     * @since 1.0.0
     */
    public static function getPmf(float $p, int $k) : float
    {
        if ($k === 0) {
            return 1 - $p;
        } elseif ($k === 1) {
            return $p;
        }

        throw new \InvalidArgumentException('k needs to be 0 or 1');
    }

    /**
     * Get cumulative distribution function.
     *
     * @param float $p Value p
     * @param float $k Value k
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getCdf(float $p, float $k) : float
    {
        if ($k < 0) {
            return 0;
        } elseif ($k >= 1) {
            return 1;
        }

        return 1 - $p;
    }

    /**
     * Get mode.
     *
     * @param float $p Value p
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function getMode(float $p) : int
    {
        if ($p === 0.5) {
            return 0;
        } elseif ($p > 0.5) {
            return 1;
        }

        return 0;
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
        return $p;
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
        if ($p === 0.5) {
            return 0.5;
        } elseif ($p > 0.5) {
            return 1;
        }

        return 0.0;
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
        return $p * (1 - $p);
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
        return (1 - $p) + $p * \exp($t);
    }

    /**
     * Get skewness.
     *
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSkewness(float $p) : float
    {
        return (1 - 2 * $p) / \sqrt($p * (1 - $p));
    }

    /**
     * Get entropy.
     *
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getEntropy(float $p) : float
    {
        return -(1 - $p) * \log(1 - $p) - $p * \log($p);
    }

    /**
     * Get Fisher information.
     *
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getFisherInformation(float $p) : float
    {
        return 1 / ($p * (1 - $p));
    }

    /**
     * Get Ex. kurtosis.
     *
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getExKurtosis(float $p) : float
    {
        return (1 - 6 * $p * (1 - $p)) / ($p * (1 - $p));
    }
}
