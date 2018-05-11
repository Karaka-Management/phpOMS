<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Business\Finance
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Business\Finance;

/**
 * Depreciation class.
 *
 * @package    phpOMS\Business\Finance
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class Depreciation
{
    /**
     * Calculate linear depretiation rate
     *
     * @param float $start    Value to depreciate
     * @param int   $duration Useful life time
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getStraightLineDepreciation(float $start, int $duration) : float
    {
        return $start / $duration;
    }

    /**
     * Calculate the residual after a specific amount of time
     *
     * @param float $start    Value to depreciate
     * @param int   $duration Useful life time
     * @param int   $t        Time passed
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getStraightLineResidualInT(float $start, int $duration, int $t) : float
    {
        return $start - self::getStraightLineDepreciation($start, $duration) * $t;
    }

    /**
     * Calculate the degression factor
     * 
     * This factor is the amount of years 
     *
     * @param float $start    Value to depreciate (reduced by residual value if required)
     * @param float $residual Residual value
     * @param int   $duration Useful life time
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getArithmeticDegressivDepreciationFactor(float $start, float $residual, int $duration) : float
    {
        return ($start - $residual) / ($duration * ($duration + 1) / 2);
    }

    /**
     * Calculate the depreciation value in period t
     *
     * @param float $start    Value to depreciate (reduced by residual value if required)
     * @param float $residual Residual value
     * @param int   $duration Useful life time
     * @param int   $t        Period
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getArithmeticDegressivDepreciationInT(float $start, float $residual, int $duration, int $t) : float
    {
        return self::getArithmeticDegressivDepreciationFactor($start, $residual, $duration) * ($duration - $t + 1);
    }

    /**
     * Calculate the residual value after some periods
     *
     * @param float $start    Value to depreciate (reduced by residual value if required)
     * @param float $residual Residual value
     * @param int   $duration Useful life time
     * @param int   $t        Passed periods
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getArithmeticDegressivDepreciationResidualInT(float $start, float $residual, int $duration, int $t) : float
    {
        $end = $start;

        for ($i = 1; $i <= $t; ++$i) {
            $end -= self::getArithmeticDegressivDepreciationInT($start, $residual, $duration, $i);
        }

        return $end;
    }

    /**
     * Calculate the progressiv factor
     *
     * @param float $start    Value to depreciate (reduced by residual value if required)
     * @param float $residual Residual value
     * @param int   $duration Useful life time
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getArithmeticProgressivDepreciationFactor(float $start, float $residual, int $duration) : float
    {
        return ($start - $residual) / ($duration * ($duration + 1) / 2);
    }

    /**
     * Calculate the depreciation value in period t
     *
     * @param float $start    Value to depreciate (reduced by residual value if required)
     * @param float $residual Residual value
     * @param int   $duration Useful life time
     * @param int   $t        Period
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getArithmeticProgressivDepreciationInT(float $start, float $residual, int $duration, int $t) : float
    {
        return self::getArithmeticProgressivDepreciationFactor($start, $residual, $duration) * $t;
    }

    /**
     * Calculate the residual value after some periods
     *
     * @param float $start    Value to depreciate (reduced by residual value if required)
     * @param float $residual Residual value
     * @param int   $duration Useful life time
     * @param int   $t        Passed periods
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getArithmeticProgressivDepreciationResidualInT(float $start, float $residual, int $duration, int $t) : float
    {
        return $start - self::getArithmeticProgressivDepreciationFactor($start, $residual, $duration) * $t * ($t + 1) / 2; 
    }

    /**
     * Calculate the depreciation rate
     *
     * @param float $start    Value to depreciate (reduced by residual value if required)
     * @param float $residual Residual value
     * @param int   $duration Useful life time
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getGeometicProgressivDepreciationRate(float $start, float $residual, int $duration) : float
    {
        return (1 - pow($residual / $start, 1 / $duration));
    }

    /**
     * Calculate the depreciation value in a period
     *
     * @param float $start    Value to depreciate (reduced by residual value if required)
     * @param float $residual Residual value
     * @param int   $duration Useful life time
     * @param int   $t        Period
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getGeometicProgressivDepreciationInT(float $start, float $residual, int $duration, int $t) : float
    {
        $rate = self::getGeometicProgressivDepreciationRate($start, $residual, $duration);

        return $start * (1 - $rate) ** ($duration - $t) * $rate;
    }

    /**
     * Calculate the residual value after some periods
     *
     * @param float $start    Value to depreciate (reduced by residual value if required)
     * @param float $residual Residual value
     * @param int   $duration Useful life time
     * @param int   $t        Period
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getGeometicProgressivDepreciationResidualInT(float $start, float $residual, int $duration, int $t) : float
    {
        $end = $start;

        for ($i = 1; $i <= $t; ++$i) {
            $end -= self::getGeometicProgressivDepreciationInT($start, $residual, $duration, $i);
        }

        return $end;
    }

    /**
     * Calculate the depreciation rate
     *
     * @param float $start    Value to depreciate (reduced by residual value if required)
     * @param float $residual Residual value
     * @param int   $duration Useful life time
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getGeometicDegressivDepreciationRate(float $start, float $residual, int $duration) : float
    {
        return (1 - pow($residual / $start, 1 / $duration));
    }

    /**
     * Calculate the depreciation value in a period
     *
     * @param float $start    Value to depreciate (reduced by residual value if required)
     * @param float $residual Residual value
     * @param int   $duration Useful life time
     * @param int   $t        Period
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getGeometicDegressivDepreciationInT(float $start, float $residual, int $duration, int $t) : float
    {
        $rate = self::getGeometicDegressivDepreciationRate($start, $residual, $duration);
        return $start * (1 - $rate) ** ($t - 1) * $rate;
    }

    /**
     * Calculate the residual value after some periods
     *
     * @param float $start    Value to depreciate (reduced by residual value if required)
     * @param float $residual Residual value
     * @param int   $duration Useful life time
     * @param int   $t        Period
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getGeometicDegressivDepreciationResidualInT(float $start, float $residual, int $duration, int $t) : float
    {
        return $start * (1 - self::getGeometicDegressivDepreciationRate($start, $residual, $duration)) ** $t;
    }
}
