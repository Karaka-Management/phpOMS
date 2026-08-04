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

use phpOMS\Math\Functions\Beta;

/**
 * Log distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class LogDistribution
{
    /**
     * Epsilon for float comparison.
     *
     * @var float
     * @since 1.0.0
     */
    public const EPSILON = 4.88e-04;

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
     * @latex -\frac{1}{\log(1-p)} \cdot \frac{p^k}{k}
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
        return -1 / \log(1 - $p) * $p ** $k / $k;
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
        // This is a workaround!
        // Actually 0 should be used instead of self::EPSILON.
        // This is only used because the incomplete beta function doesn't work for p or q = 0
        return 1 + Beta::incompleteBeta($p, $k + 1, self::EPSILON) / \log(1 - $p);
    }

    /**
     * Get expected value.
     *
     * @latex -\frac{1}{\log(1-p)} \cdot \frac{p}{1-p}
     *
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMean(float $p) : float
    {
        return -1 / \log(1 - $p) * $p / (1 - $p);
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
     * Get variance.
     *
     * @latex -\frac{p^2 + p\log(1-p)}{(1-p)^2\log(1-p)^2}
     *
     * @param float $p Value p
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVariance(float $p) : float
    {
        return -($p ** 2 + $p * \log(1 - $p))
            / ((1 - $p) ** 2 * \log(1 - $p) ** 2);
    }

    /**
     * Get standard deviation.
     *
     * @latex \sqrt{-\frac{p^2 + p\log(1-p)}{(1-p)^2\log(1-p)^2}}
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
     * @latex \frac{\log(1-p\exp(t))}{\log(1-p)}
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
        return \log(1 - $p * \exp($t)) / \log(1 - $p);
    }
}
