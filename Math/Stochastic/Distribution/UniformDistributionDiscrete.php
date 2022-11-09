<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Stochastic\Distribution
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Stochastic\Distribution;

/**
 * Uniform (discrete) distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class UniformDistributionDiscrete
{
    /**
     * Get probability mass function.
     *
     * @param float $a Value a
     * @param float $b Value b
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPmf(float $a, float $b) : float
    {
        return 1 / ($b - $a + 1);
    }

    /**
     * Get cumulative distribution function.
     *
     * @param float $k Value k element of [a, b]
     * @param float $a Value a
     * @param float $b Value b
     *
     * @return float
     *
     * @throws \OutOfBoundsException
     *
     * @since 1.0.0
     */
    public static function getCdf(float $k, float $a, float $b) : float
    {
        if ($k > $b || $k < $a) {
            throw new \OutOfBoundsException('Out of bounds');
        }

        return (\floor($k) - $a + 1) / ($b - $a + 1);
    }

    /**
     * Get moment generating function.
     *
     * @param int   $t Value t
     * @param float $a Value a
     * @param float $b Value b
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMgf(int $t, float $a, float $b) : float
    {
        return (\exp($a * $t) - \exp(($b + 1) * $t))
            / (($b - $a + 1) * (1 - \exp($t)));
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
        return 0.0;
    }

    /**
     * Get Ex. kurtosis.
     *
     * @param float $a Value a
     * @param float $b Value b
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getExKurtosis(float $a, float $b) : float
    {
        $n = ($b - $a + 1);

        return -6 / 5 * ($n ** 2 + 1) / ($n ** 2 - 1);
    }

    /**
     * Get median.
     *
     * @param float $a Value a
     * @param float $b Value b
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMedian(float $a, float $b) : float
    {
        return ($a + $b) / 2;
    }

    /**
     * Get expected value.
     *
     * @param float $a Value a
     * @param float $b Value b
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMean(float $a, float $b) : float
    {
        return ($a + $b) / 2;
    }

    /**
     * Get variance.
     *
     * @param float $a Value a
     * @param float $b Value b
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVariance(float $a, float $b) : float
    {
        return (($b - $a + 1) ** 2 - 1) / 12;
    }

    /**
     * Get standard deviation.
     *
     * @param float $a Value a
     * @param float $b Value b
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getStandardDeviation(float $a, float $b) : float
    {
        return \sqrt(self::getVariance($a, $b));
    }
}
