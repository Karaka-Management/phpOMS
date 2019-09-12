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
 * Cauchy distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class CauchyDistribution
{
    /**
     * Get probability density function.
     *
     * @param float $x     Value x
     * @param float $x0    Value x0
     * @param float $gamma Gamma
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPdf(float $x, float $x0, float $gamma) : float
    {
        return 1 / (\M_PI * $gamma * (1 + (($x - $x0) / $gamma) ** 2));
    }

    /**
     * Get cumulative distribution function.
     *
     * @param float $x     Value x
     * @param float $x0    Value x0
     * @param float $gamma Gamma
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getCdf(float $x, float $x0, float $gamma) : float
    {
        return 1 / \M_PI * \atan(($x - $x0) / $gamma) + 0.5;
    }

    /**
     * Get mode.
     *
     * @param float $x0 Value x0
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMode($x0) : float
    {
        return $x0;
    }

    /**
     * Get expected value.
     *
     * @param float $x0 Value x0
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMedian(float $x0) : float
    {
        return $x0;
    }

    /**
     * Get entropy.
     *
     * @param float $gamma Gamma / scale parameter
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getEntropy(float $gamma) : float
    {
        return \log(4 * \M_PI * $gamma);
    }
}
