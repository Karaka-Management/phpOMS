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
     * @param float $margin        Margin per period
     * @param float $retentionRate Rate of remaining customers per period
     * @param float $discountRate  Cost of capital to discound future revenue
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
     * @param array $revenues Revenues
     * @param int   $periods  Amount of revenue periods
     * @param float $cutoff   Normalization cutoff (which values should be ignored)
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMRR(array $revenues, int $periods = 12, float $cutoff = 0.1) : float
    {
        if ($cutoff === 0.0) {
            return \array_sum($revenues) / $periods;
        }

        $count  = \count($revenues);
        $offset = (int) \round($count * $cutoff, 0, \PHP_ROUND_HALF_UP);

        if ($offset * 2 >= $count) {
            return 0.0;
        }

        \sort($revenues);
        $normalized = \array_splice($revenues, $offset, $count - $offset);

        return \array_sum($normalized) / $periods;
    }
}
