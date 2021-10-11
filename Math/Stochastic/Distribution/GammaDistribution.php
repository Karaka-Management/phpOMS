<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Math\Stochastic\Distribution
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);
namespace phpOMS\Math\Stochastic\Distribution;

use phpOMS\Math\Functions\Gamma;

/**
 * Gamma distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class GammaDistribution
{
    /**
     * Get probability density function for shape and scale.
     *
     * @param float $x     Value x
     * @param float $k     k shape
     * @param float $theta Theta scale
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPdfScale(float $x, float $k, float $theta) : float
    {
        return 1 / (Gamma::gamma($k) * $theta ** $k) * pow($x, $k - 1) * exp(-$x / $theta);
    }

    /**
     * Get probability density function for shape and rate.
     *
     * @param float $x     Value x
     * @param float $alpha Alpha shape
     * @param float $beta  Beta rate
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPdfRate(float $x, float $alpha, float $beta) : float
    {
        return $beta ** $alpha / Gamma::gamma($alpha) * pow($x, $alpha - 1) * exp(-$beta * $x);
    }

    /**
     * Get probability density function for shape and scale.
     *
     * @param float $x     Value x
     * @param int   $k     k shape
     * @param float $theta Theta scale
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPdfIntegerScale(float $x, int $k, float $theta) : float
    {
        return 1 / (Gamma::getGammaInteger($k) * $theta ** $k) * pow($x, $k - 1) * exp(-$x / $theta);
    }

    /**
     * Get probability density function for shape and rate.
     *
     * @param float $x     Value x
     * @param int   $alpha Alpha shape
     * @param float $beta  Beta rate
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPdfIntegerRate(float $x, int $alpha, float $beta) : float
    {
        return $beta ** $alpha / Gamma::getGammaInteger($alpha) * pow($x, $alpha - 1) * exp(-$beta * $x);
    }

    /**
     * Get cumulative density function for shape and scale.
     *
     * @param float $x     Value x
     * @param float $k     k shape
     * @param float $theta Theta scale
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getCdfScale(float $x, float $k, float $theta) : float
    {
        return 1 / Gamma::gamma($k) * Gamma::incompleteGammaFirst($k, $x / $theta);
    }

    /**
     * Get cumulative density function for shape and rate.
     *
     * @param float $x     Value x
     * @param float $alpha Alpha shape
     * @param float $beta  Beta rate
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getCdfRate(float $x, float $alpha, float $beta) : float
    {
        return 1 / Gamma::gamma($alpha) * Gamma::incompleteGammaFirst($alpha, $beta * $x);
    }

    /**
     * Get expected value.
     *
     * @param float $k     k shape
     * @param float $theta Theta scale
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMeanScale(float $k, float $theta) : float
    {
        return $k * $theta;
    }

    /**
     * Get expected value.
     *
     * @param float $alpha Alpha shape
     * @param float $beta  Beta rate
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMeanRate(float $alpha, float $beta) : float
    {
        return $alpha / $beta;
    }

    /**
     * Get mode.
     *
     * @param float $k     k shape
     * @param float $theta Theta scale
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getModeScale(float $k, float $theta) : float
    {
        return ($k - 1) * $theta;
    }

    /**
     * Get mode.
     *
     * @param float $alpha Alpha shape
     * @param float $beta  Beta scale
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getModeRate(float $alpha, float $beta) : float
    {
        return ($alpha - 1) / $beta;
    }

    /**
     * Get skewness.
     *
     * @param float $k Shape k or alpha
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSkewness(float $k) : float
    {
        return 2 / sqrt($k);
    }

    /**
     * Get Ex. kurtosis.
     *
     * @param float $k Shape k or alpha
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getExKurtosis(float $k) : float
    {
        return 6 / $k;
    }

    /**
     * Get variance.
     *
     * @param float $k     k shape
     * @param float $theta Theta scale
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVarianceScale(float $k, float $theta) : float
    {
        return $k * $theta ** 2;
    }

    /**
     * Get standard deviation.
     *
     * @param float $k     k shape
     * @param float $theta Theta scale
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getStandardDeviationScale(float $k, float $theta) : float
    {
        return sqrt(self::getVarianceScale($k, $theta));
    }

    /**
     * Get variance.
     *
     * @param float $alpha Alpha shape
     * @param float $beta  Beta scale
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVarianceRate(float $alpha, float $beta) : float
    {
        return $alpha / ($beta ** 2);
    }

    /**
     * Get standard deviation.
     *
     * @param float $alpha Alpha shape
     * @param float $beta  Beta scale
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getStandardDeviationRate(float $alpha, float $beta) : float
    {
        return sqrt(self::getVarianceRate($alpha, $beta));
    }

    /**
     * Get moment generating function.
     *
     * @param float $k     k shape
     * @param float $t     Value t
     * @param float $theta Theta scale
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMgfScale(float $k, float $t, float $theta) : float
    {
        return pow(1 - $theta * $t, -$k);
    }

    /**
     * Get moment generating function.
     *
     * @param float $t     Value t
     * @param float $alpha Alpha shape
     * @param float $beta  Beta scale
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMgfRate(float $t, float $alpha, float $beta) : float
    {
        return pow(1 - $t / $beta, -$alpha);
    }
}
