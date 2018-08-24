<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Math\Stochastic\Distribution
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Math\Stochastic\Distribution;

/**
 * Bernulli distribution.
 *
 * @package    phpOMS\Math\Stochastic\Distribution
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class BernoulliDistribution
{
    /**
     * Get probability mass function.
     *
     * @param float $p Value p
     * @param int   $k Value k
     *
     * @return float
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public static function getPmf(float $p, int $k) : float
    {
        if ($k === 0) {
            return 1 - $p;
        } elseif ($k === 1) {
            return $p;
        }

        throw new \Exception('wrong parameter');
    }

    /**
     * Get cummulative distribution function.
     *
     * @param float $p Value p
     * @param float $k Value k
     *
     * @return float
     *
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public static function getMean(float $p) : float
    {
        return $p;
    }

    /**
     * Get expected value.
     *
     * @param float $p Value p
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getMedian(float $p) : float
    {
        if ($p === 0.5) {
            return 0.5;
        } elseif ($p > 0.5) {
            return 1;
        }

        return 0;
    }

    /**
     * Get variance.
     *
     * @param float $p Value p
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getVariance(float $p) : float
    {
        return $p * (1 - $p);
    }

    /**
     * Get moment generating function.
     *
     * @param float $p Value p
     * @param float $t Value t
     *
     * @return float
     *
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public static function getExKurtosis(float $p) : float
    {
        return (1 - 6 * $p * (1 - $p)) / ($p * (1 - $p));
    }

    public static function getRandom()
    {

    }
}
