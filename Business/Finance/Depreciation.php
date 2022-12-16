<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Business\Finance
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Business\Finance;

/**
 * Depreciation class.
 *
 * @package phpOMS\Business\Finance
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Depreciation
{
    /**
     * Constructor
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Calculate linear depretiation rate
     *
     * @param float $start    Value to depreciate
     * @param int   $duration Useful life time
     *
     * @return float Returns the straight line depreciation
     *
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public static function getArithmeticDegressiveDepreciationFactor(float $start, float $residual, int $duration) : float
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
     * @since 1.0.0
     */
    public static function getArithmeticDegressiveDepreciationInT(float $start, float $residual, int $duration, int $t) : float
    {
        return self::getArithmeticDegressiveDepreciationFactor($start, $residual, $duration) * ($duration - $t + 1);
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
     * @since 1.0.0
     */
    public static function getArithmeticDegressiveDepreciationResidualInT(float $start, float $residual, int $duration, int $t) : float
    {
        $end = $start;

        for ($i = 1; $i <= $t; ++$i) {
            $end -= self::getArithmeticDegressiveDepreciationInT($start, $residual, $duration, $i);
        }

        return $end;
    }

    /**
     * Calculate the progressivee factor
     *
     * @param float $start    Value to depreciate (reduced by residual value if required)
     * @param float $residual Residual value
     * @param int   $duration Useful life time
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getArithmeticProgressiveDepreciationFactor(float $start, float $residual, int $duration) : float
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
     * @since 1.0.0
     */
    public static function getArithmeticProgressiveDepreciationInT(float $start, float $residual, int $duration, int $t) : float
    {
        return self::getArithmeticProgressiveDepreciationFactor($start, $residual, $duration) * $t;
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
     * @since 1.0.0
     */
    public static function getArithmeticProgressiveDepreciationResidualInT(float $start, float $residual, int $duration, int $t) : float
    {
        return $start - self::getArithmeticProgressiveDepreciationFactor($start, $residual, $duration) * $t * ($t + 1) / 2;
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
     * @since 1.0.0
     */
    public static function getGeometicProgressiveDepreciationRate(float $start, float $residual, int $duration) : float
    {
        return (1 - \pow($residual / $start, 1 / $duration));
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
     * @since 1.0.0
     */
    public static function getGeometicProgressiveDepreciationInT(float $start, float $residual, int $duration, int $t) : float
    {
        $rate = self::getGeometicProgressiveDepreciationRate($start, $residual, $duration);

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
     * @since 1.0.0
     */
    public static function getGeometicProgressiveDepreciationResidualInT(float $start, float $residual, int $duration, int $t) : float
    {
        $end = $start;

        for ($i = 1; $i <= $t; ++$i) {
            $end -= self::getGeometicProgressiveDepreciationInT($start, $residual, $duration, $i);
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
     * @since 1.0.0
     */
    public static function getGeometicDegressiveDepreciationRate(float $start, float $residual, int $duration) : float
    {
        return (1 - \pow($residual / $start, 1 / $duration));
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
     * @since 1.0.0
     */
    public static function getGeometicDegressiveDepreciationInT(float $start, float $residual, int $duration, int $t) : float
    {
        $rate = self::getGeometicDegressiveDepreciationRate($start, $residual, $duration);
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
     * @since 1.0.0
     */
    public static function getGeometicDegressiveDepreciationResidualInT(float $start, float $residual, int $duration, int $t) : float
    {
        return $start * (1 - self::getGeometicDegressiveDepreciationRate($start, $residual, $duration)) ** $t;
    }
}
