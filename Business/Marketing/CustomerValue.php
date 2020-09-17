<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Business\Marketing
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Business\Marketing;

/**
 * Marketing CustomerValue
 *
 * @package phpOMS\Business\Marketing
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class CustomerValue
{
    /**
     * Simple customer lifetime value
     *
     * Hazard Model, same as $margin * (1 + $discountRate) / (1 + $discountRate - $retentionRate)
     *
     * @param float $margin        Margin per period
     * @param float $retentionRate Rate of remaining customers per period (= average lifetime / (1 + average lifetime))
     * @param float $discountRate  Cost of capital to discount future revenue
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSimpleCLV(float $margin, float $retentionRate, float $discountRate) : float
    {
        return $margin * $retentionRate / (1 + $discountRate - $retentionRate);
    }

    /**
     * Normalized measure of recurring revenue
     *
     * @param array $revenues    Revenues
     * @param int   $periods     Amount of revenue periods
     * @param float $lowerCutoff Normalization cutoff (which lower values should be ignored)
     * @param float $upperCutoff Normalization cutoff (which upper values should be ignored)
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMRR(array $revenues, int $periods = 12, float $lowerCutoff = 0.1, float $upperCutoff = 0.0) : float
    {
        if ($lowerCutoff === 0.0 && $upperCutoff === 0.0) {
            return \array_sum($revenues) / $periods;
        }

        \sort($revenues);

        $sum = 0.0;
        foreach ($revenues as $revenue) {
            if ($revenue >= $lowerCutoff && $revenue <= $upperCutoff) {
                $sum += $revenue;
            }
        }

        return $sum / $periods;
    }
}
