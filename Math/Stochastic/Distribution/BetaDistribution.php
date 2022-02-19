<?php
/**
 * Karaka
 *
 * PHP Version 8.0
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
use phpOMS\Math\Functions\Functions;

/**
 * Beta distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class BetaDistribution
{
    /**
     * Get expected value.
     *
     * @param float $alpha Alpha
     * @param float $beta  Beta
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMean(float $alpha, float $beta) : float
    {
        return $alpha / ($alpha + $beta);
    }

    /**
     * Get mode.
     *
     * @param float $alpha Alpha
     * @param float $beta  Beta
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMode(float $alpha, float $beta) : float
    {
        if ($alpha > 1 && $beta > 1) {
            return ($alpha - 1) / ($alpha + $beta - 2);
        }

        if ($alpha <= 1.0 && $beta > 1) {
            return 0.0;
        }

        return 1.0;
    }

    /**
     * Get variance.
     *
     * @param float $alpha Alpha
     * @param float $beta  Beta
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVariance(float $alpha, float $beta) : float
    {
        return $alpha * $beta / (($alpha + $beta) ** 2 * ($alpha + $beta + 1));
    }

    /**
     * Get standard deviation.
     *
     * @param float $alpha Alpha
     * @param float $beta  Beta
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getStandardDeviation(float $alpha, float $beta) : float
    {
        return \sqrt(self::getVariance($alpha, $beta));
    }

    /**
     * Get skewness.
     *
     * @param float $alpha Alpha
     * @param float $beta  Beta
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSkewness(float $alpha, float $beta) : float
    {
        return 2 * ($beta - $alpha) * \sqrt($alpha + $beta + 1) / (($alpha + $beta + 2) * \sqrt($alpha * $beta));
    }

    /**
     * Get Ex. kurtosis.
     *
     * @param float $alpha Alpha
     * @param float $beta  Beta
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getExKurtosis(float $alpha, float $beta) : float
    {
        return 6 * (($alpha - $beta) ** 2 * ($alpha + $beta + 1) - $alpha * $beta * ($alpha + $beta + 2))
            / ($alpha * $beta * ($alpha + $beta + 2) * ($alpha + $beta + 3));
    }

    /**
     * Get moment generating function.
     *
     * @param float $t     Value t
     * @param float $alpha Alpha
     * @param float $beta  Beta
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMgf(float $t, float $alpha, float $beta) : float
    {
        $sum = 0;
        for ($k = 1; $k < 15; ++$k) {
            $product = 1;
            for ($r = 0; $r < $k; ++$r) {
                $product *= ($alpha + $r) / ($alpha + $beta + $r);
            }

            $sum += $product * $t ** $k / Functions::fact($k);
        }

        return 1 + $sum;
    }

    /**
     * Get prdobability distribution function.
     *
     * @param float $x     Value
     * @param float $alpha Alpha
     * @param float $beta  Beta
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPdf(float $x, float $alpha, float $beta) : float
    {
        return \pow($x, $alpha - 1) * \pow(1 - $x, $beta - 1) / Beta::beta($alpha, $beta);
    }

    /**
     * Get cumulative distribution function.
     *
     * @param float $x     Value
     * @param float $alpha Alpha
     * @param float $beta  Beta
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getCdf(float $x, float $alpha, float $beta) : float
    {
        return Beta::regularizedBeta($x, $alpha, $beta);
    }
}
