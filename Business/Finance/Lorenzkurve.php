<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Business\Finance
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Business\Finance;

/**
 * Finance class.
 *
 * @package phpOMS\Business\Finance
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Lorenzkurve
{
    /**
     * Calculate Gini coefficient
     *
     * @param array<float|int> $data Datapoints (can be unsorted)
     *
     * @return float Returns the gini coefficient
     *
     * @since 1.0.0
     */
    public static function getGiniCoefficient(array $data) : float
    {
        $sum1 = 0;
        $sum2 = 0;
        $i    = 1;
        $n    = \count($data);

        \sort($data);

        foreach ($data as $key => $value) {
            $sum1 += $i * $value;
            $sum2 += $value;
            ++$i;
        }

        return 2 * $sum1 / ($n * $sum2) - ($n + 1) / $n;
    }
}
