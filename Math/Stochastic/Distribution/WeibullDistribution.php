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
 * Weibull distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class WeibullDistribution
{
    /**
     * Get probability density function.
     *
     * @param float $x      Value x
     * @param float $lambda Scale lambda
     * @param float $k      Shape k
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPdf(float $x, float $lambda, float $k) : float
    {
        return $x < 0.0 ? 0.0 : $k / $lambda * \pow($x / $lambda, $k - 1) * \exp(-($x / $lambda) ** $k);
    }

    /**
     * Get cumulative distribution function.
     *
     * @param float $x      Value x
     * @param float $lambda Scale lambda
     * @param float $k      Shape k
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getCdf(float $x, float $lambda, float $k) : float
    {
        return $x < 0.0 ? 0.0 : 1 - \exp(-($x / $lambda) ** $k);
    }

    /**
     * Get median.
     *
     * @param float $lambda Scale lambda
     * @param float $k      Shape k
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMedian(float $lambda, float $k) : float
    {
        return $lambda * \pow(\log(2), 1 / $k);
    }

    /**
     * Get mode.
     *
     * @param float $lambda Scale lambda
     * @param float $k      Shape k
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMode(float $lambda, float $k) : float
    {
        return $lambda * \pow(($k - 1) / $k, 1 / $k);
    }

    /**
     * Get entropy.
     *
     * @param float $lambda Scale lambda
     * @param float $k      Shape k
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getEntropy(float $lambda, float $k) : float
    {
        $gamma = 0.57721566490153286060651209008240243104215933593992;

        return $gamma * (1 - 1 / $k) + \log($lambda / $k) + 1;
    }

    /**
     * Weibull-Distribution
     *
     * @param float $value        Value
     * @param float $alpha        Alpha
     * @param float $beta         Beta
     * @param bool  $isCumulative Cumulative
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function dist(float $value, float $alpha, float $beta, bool $isCumulative = true) : float
    {
        return $isCumulative
            ? 1 - \exp(-\pow($value / $beta, $alpha))
            : $alpha / \pow($beta, $alpha) * \pow($value, $alpha - 1) * \exp(-\pow($value / $beta, $alpha));
    }
}
