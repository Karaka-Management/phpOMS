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

use phpOMS\Math\Functions\Beta;

/**
 * F distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class FDistribution
{
    /**
     * Get probability density function.
     *
     * @param float $x  Value x
     * @param int   $d1 Degreegs of freedom
     * @param int   $d2 Degreegs of freedom
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPdf(float $x, int $d1, int $d2) : float
    {
        return \sqrt((\pow($d1 * $x, $d1) * ($d2 ** $d2)) / \pow($d1 * $x + $d2, $d1 + $d2))
            / ($x * Beta::beta($d1 / 2, $d2 / 2));
    }

    /**
     * Get cumulative density function.
     *
     * @param float $x  Value x
     * @param int   $d1 Degreegs of freedom
     * @param int   $d2 Degreegs of freedom
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getCdf(float $x, int $d1, int $d2) : float
    {
        return Beta::regularizedBeta($d1 * $x / ($d1 * $x + $d2), $d1 / 2, $d2 / 2);
    }

    /**
     * Get expected value.
     *
     * @param int $d2 Degree of freedom
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMean(int $d2) : float
    {
        if ($d2 === 2) {
            return 0.0;
        }

        return $d2 / ($d2 - 2);
    }

    /**
     * Get mode.
     *
     * @param int $d1 Degree of freedom
     * @param int $d2 Degree of freedom
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMode(int $d1, int $d2) : float
    {
        if ($d1 === 0) {
            return 0.0;
        }

        return ($d1 - 2) / $d1 * $d2 / ($d2 + 2);
    }

    /**
     * Get variance.
     *
     * @param int $d1 Degree of freedom
     * @param int $d2 Degree of freedom
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVariance(int $d1, int $d2) : float
    {
        if ($d2 === 2 || $d2 === 4) {
            return 0.0;
        }

        return 2 * $d2 ** 2 * ($d1 + $d2 - 2)
            / ($d1 * ($d2 - 2) ** 2 * ($d2 - 4));
    }

    /**
     * Get standard deviation.
     *
     * @param int $d1 Degree of freedom
     * @param int $d2 Degree of freedom
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getStandardDeviation(int $d1, int $d2) : float
    {
        return \sqrt(self::getVariance($d1, $d2));
    }

    /**
     * Get skewness.
     *
     * @param int $d1 Degree of freedom
     * @param int $d2 Degree of freedom
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSkewness(int $d1, int $d2) : float
    {
        if ($d2 < 7) {
            return 0.0;
        }

        return (2 * $d1 + $d2 - 2) * \sqrt(8 * ($d2 - 4))
            / (($d2 - 6) * \sqrt($d1 * ($d1 + $d2 - 2)));
    }
}
