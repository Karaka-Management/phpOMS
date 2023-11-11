<?php
/**
 * Jingga
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
 * Cauchy distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class CauchyDistribution
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
    public static function getMode(float $x0) : float
    {
        return $x0;
    }

    /**
     * Get median.
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
