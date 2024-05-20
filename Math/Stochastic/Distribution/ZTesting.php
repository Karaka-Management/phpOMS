<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Math\Stochastic\Distribution
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Stochastic\Distribution;

use phpOMS\Math\Statistic\Average;
use phpOMS\Math\Statistic\MeasureOfDispersion;

/**
 * ZTest
 *
 * Test if the mean is the same as the population mean
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @internal
 */
final class ZTesting
{
    /**
     * Percentile table
     *
     * @var array<string, float>
     * @since 1.0.0
     */
    public const TABLE = [
        '0.99' => 2.58,
        '0.98' => 2.33,
        '0.95' => 1.96,
        '0.90' => 1.64,
        '0.85' => 1.44,
        '0.80' => 1.28,
    ];

    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Test hypthesis.
     *
     * @param float $dataset      Value observed
     * @param float $expected     Expected value
     * @param float $total        Observed dataset size
     * @param float $significance Significance
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function testHypothesis(float $dataset, float $expected, float $total, float $significance = 0.95) : bool
    {
        $z = ($dataset - $expected) / \sqrt($expected * (1 - $expected) / $total);

        $zSignificance = 0.0;
        foreach (self::TABLE as $key => $value) {
            if ($significance === (float) $key) {
                $zSignificance = $value;
            }
        }

        return $z > -$zSignificance && $z < $zSignificance;
    }

    /**
     * Z-TEST.
     *
     * @param float      $value Value to test
     * @param array      $data  Data
     * @param null|float $sigma Sigma / Significance
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function zTest(float $value, array $data, ?float $sigma = null) : float
    {
        $sigma ??= MeasureOfDispersion::standardDeviationSample($data);

        return 1 - NormalDistribution::getCdf((Average::arithmeticMean($data) - $value) / ($sigma / \sqrt(\count($data))), 0.0, 1.0);
    }

    /**
     * Z-TEST.
     *
     * @param float $value    Value to test
     * @param float $mean     Mean
     * @param int   $dataSize Data size
     * @param float $sigma    Sigma / Significance
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function zTestValues(float $value, float $mean, int $dataSize, float $sigma) : float
    {
        return 1 - NormalDistribution::getCdf(($mean - $value) / ($sigma / \sqrt($dataSize)), 0.0, 1.0);
    }
}
