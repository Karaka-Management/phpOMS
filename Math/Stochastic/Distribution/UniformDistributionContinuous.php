<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */

namespace phpOMS\Math\Stochastic\Distribution;

/**
 * Uniform (continuous) distribution.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class UniformDistributionContinuous
{

    /**
     * Get mode.
     *
     * @param float $a
     * @param float $b
     *
     * @return float
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getMode(float $a, float $b) : float
    {
        return ($a + $b) / 2;
    }

    /**
     * Get probability density function.
     *
     * @param float $x
     * @param float $a
     * @param float $b
     *
     * @return float
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getPdf(float $x, float $a, float $b) : float
    {
        return $x > $a && $x < $b ? 1 / ($b - $a) : 0;
    }

    /**
     * Get cumulative distribution function.
     *
     * @param float $x
     * @param float $a
     * @param float $b
     *
     * @return float
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getCdf(float $x, float $a, float $b) : float
    {
        if ($x < $a) {
            return 0;
        } elseif ($x >= $a && $x < $b) {
            return ($x - $a) / ($b - $a);
        } else {
            return 1;
        }
    }

    /**
     * Get moment generating function.
     *
     * @param int   $t
     * @param float $a
     * @param float $b
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getMgf(int $t, float $a, float $b) : float
    {
        return $t === 0 ? 1 : (exp($t * $b) - exp($t * $a)) / ($t * ($b - $a));
    }

    /**
     * Get skewness.
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getSkewness() : float
    {
        return 0.0;
    }

    /**
     * Get Ex. kurtosis.
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getExKurtosis() : float
    {
        return -6 / 5;
    }

    /**
     * Get expected value.
     *
     * @param float $a
     * @param float $b
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getMedian(float $a, float $b) : float
    {
        return ($a + $b) / 2;
    }

    /**
     * Get expected value.
     *
     * @param float $a
     * @param float $b
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getMean(float $a, float $b) : float
    {
        return ($a + $b) / 2;
    }

    /**
     * Get variance.
     *
     * @param float $a
     * @param float $b
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getVariance(float $a, float $b) : float
    {
        return 1 / 12 * ($b - $a) ** 2;
    }

    public static function getRandom()
    {

    }
}
