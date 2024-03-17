<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Business\Finance
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Business\Finance;

/**
 * Finance class.
 *
 * @package phpOMS\Business\Finance
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Lorenzkurve
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
     * Calculate Gini coefficient
     *
     * @param array<int|float> $data Data points (can be unsorted)
     *
     * @return float Returns the Gini coefficient
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
