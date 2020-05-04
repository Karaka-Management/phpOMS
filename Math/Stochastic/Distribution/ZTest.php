<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Math\Stochastic\Distribution
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Stochastic\Distribution;

use phpOMS\Math\Statistic\Average;
use phpOMS\Math\Statistic\MeasureOfDispersion;

/**
 * ZTest
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 *
 * @internal
 */
final class ZTest
{
    public const TABLE = [
        '2.58' => 0.99,
        '2.33' => 0.98,
        '1.96' => 0.95,
        '1.64' => 0.90,
        '1.44' => 0.85,
        '1.28' => 0.80,
    ];

    /**
     * Test hypthesis.
     *
     * @param array      $data  Data
     * @param float      $alpha Alpha / Observed dataset size
     * @param null|float $sigma Sigma / Significance
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function testHypothesis(array $data, float $alpha, float $sigma = null) : float
    {
        if ($sigma === null) {
            return MeasureOfDispersion::standardDeviationSample($data);
        }

        return 1 - NormalDistribution::dist((Average::arithmeticMean($data) - $alpha) / ($sigma / \sqrt(\count($data))), 0.0, 1.0, true);
    }
}
