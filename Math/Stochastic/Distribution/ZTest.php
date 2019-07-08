<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Math\Stochastic\Distribution
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Stochastic\Distribution;

/**
 * ZTest
 *
 * @package    phpOMS\Math\Stochastic\Distribution
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class ZTest
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
     * @param float $dataset      Value observed
     * @param float $expected     Expected value
     * @param float $total        Observed dataset size
     * @param float $significance Significance
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function testHypothesis(float $dataset, float $expected, float $total, float $significance = 0.95) : bool
    {
        $z = ($dataset - $expected) / \sqrt($expected * (1 - $expected) / $total);

        $zSignificance = 0.0;
        foreach (self::TABLE as $key => $value) {
            if ($significance === $value) {
                $zSignificance = (float) $key;
            }
        }

        return $z > -$key && $z < $key;
    }
}
