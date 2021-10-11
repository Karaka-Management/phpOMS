<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Math\Topology
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */

declare(strict_types=1);

namespace phpOMS\Math\Topology;

use phpOMS\Math\Matrix\Exception\InvalidDimensionException;

/**
 * Metrics.
 *
 * @package phpOMS\Math\Topology
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Metrics2D
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
     * Manhatten metric.
     *
     * @latex d(p, q) = \sum_{n=1}^N{|p_i - q_i|}
     *
     * @param array<string, int|float> $a 2-D array with x and y coordinate
     * @param array<string, int|float> $b 2-D array with x and y coordinate
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function manhattan(array $a, array $b) : float
    {
        return abs($a['x'] - $b['x']) + abs($a['y'] - $b['y']);
    }

    /**
     * Euclidean metric.
     *
     * @latex d(p, q) = \sqrt{\sum_{n=1}^N{(p_i - q_i)^2}}
     *
     * @param array<string, int|float> $a 2-D array with x and y coordinate
     * @param array<string, int|float> $b 2-D array with x and y coordinate
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function euclidean(array $a, array $b) : float
    {
        $dx = abs($a['x'] - $b['x']);
        $dy = abs($a['y'] - $b['y']);

        return sqrt($dx * $dx + $dy * $dy);
    }

    /**
     * Octile metric.
     *
     * @latex d(p, q) = \begin{cases}(\sqrt{2} - 1) \times |p_i - q_i| + |p_{i+1} - q_{i+1}|,& \text{if } |p_i - q_i| < |p_{i+1} - q_{i+1}|\\(\sqrt{2} - 1) \times |p_{i+1} - q_{i+1}| + |p_i - q_i|,&\text{if } |p_i - q_i| \geq |p_{i+1} - q_{i+1}|\end{cases}
     *
     * @param array<string, int|float> $a 2-D array with x and y coordinate
     * @param array<string, int|float> $b 2-D array with x and y coordinate
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function octile(array $a, array $b) : float
    {
        $dx = abs($a['x'] - $b['x']);
        $dy = abs($a['y'] - $b['y']);

        return $dx < $dy ? (sqrt(2) - 1) * $dx + $dy : (sqrt(2) - 1) * $dy + $dx;
    }

    /**
     * Chebyshev metric.
     *
     * @latex d(p, q) = \max_i{(|p_i - q_i|)}
     *
     * @param array<string, int|float> $a 2-D array with x and y coordinate
     * @param array<string, int|float> $b 2-D array with x and y coordinate
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function chebyshev(array $a, array $b) : float
    {
        return max(
            abs($a['x'] - $b['x']),
            abs($a['y'] - $b['y'])
        );
    }

    /**
     * Minkowski metric.
     *
     * @latex d(p, q) = \sqrt[\lambda]{\sum_{n=1}^N{|p_i - q_i|^\lambda}}
     *
     * @param array<string, int|float> $a      2-D array with x and y coordinate
     * @param array<string, int|float> $b      2-D array with x and y coordinate
     * @param int                      $lambda Lambda
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function minkowski(array $a, array $b, int $lambda) : float
    {
        return pow(
            pow(abs($a['x'] - $b['x']), $lambda)
            + pow(abs($a['y'] - $b['y']), $lambda),
            1 / $lambda
        );
    }

    /**
     * Canberra metric.
     *
     * @latex d(p, q) = \sum_{n=1}^N{\frac{|p_i - q_i|}{|p_i| + |q_i|}
     *
     * @param array<string, int|float> $a 2-D array with x and y coordinate
     * @param array<string, int|float> $b 2-D array with x and y coordinate
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function canberra(array $a, array $b) : float
    {
        return abs($a['x'] - $b['x']) / (abs($a['x']) + abs($b['x']))
            + abs($a['y'] - $b['y']) / (abs($a['y']) + abs($b['y']));
    }

    /**
     * Bray Curtis metric.
     *
     * @latex d(p, q) = \frac{\sum_{n=1}^N{|p_i - q_i|}}{\sum_{n=1}^N{(p_i + q_i)}}
     *
     * @param array<string, int|float> $a 2-D array with x and y coordinate
     * @param array<string, int|float> $b 2-D array with x and y coordinate
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function brayCurtis(array $a, array $b) : float
    {
        return (abs($a['x'] - $b['x'])
                + abs($a['y'] - $b['y']))
            / (($a['x'] + $b['x'])
                + ($a['y'] + $b['y']));
    }

    /**
     * Angular separation metric.
     *
     * @latex d(p, q) = \frac{\sum_{n=1}^N{p_i * q_i}}{\left(\sum_{n=1}^N{p_i^2} * \sum_{n=1}^N{q_i^2}\right)^\frac{1}{2}}
     *
     * @param array<string, int|float> $a 2-D array with x and y coordinate
     * @param array<string, int|float> $b 2-D array with x and y coordinate
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function angularSeparation(array $a, array $b) : float
    {
        return ($a['x'] * $b['x'] + $a['y'] * $b['y']) / pow(($a['x'] ** 2 + $a['y'] ** 2) * ($b['x'] ** 2 + $b['y'] ** 2), 1 / 2);
    }

    /**
     * Hamming metric.
     *
     * @latex d(p, q) = \sum_{n=1}^N{|p_i - q_i|}
     *
     * @param array<int, int|float> $a 2-D array with x and y coordinate
     * @param array<int, int|float> $b 2-D array with x and y coordinate
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function hamming(array $a, array $b) : int
    {
        if (($size = \count($a)) !== \count($b)) {
            throw new InvalidDimensionException(\count($a) . 'x' . \count($b));
        }

        $dist = 0;
        for ($i = 0; $i < $size; ++$i) {
            if ($a[$i] !== $b[$i]) {
                ++$dist;
            }
        }

        return $dist;
    }

    /**
     * Ulams metric.
     *
     * Calculate the minimum amount of changes to make two arrays the same.
     *
     * In order to use this with objects the objects would have to implement some kind of value representation for comparison.
     *
     * @param array<int, int|float> $a Array with elements
     * @param array<int, int|float> $b Array with same elements but different order
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function ulam(array $a, array $b) : int
    {
        if (($size = \count($a)) !== \count($b)) {
            throw new InvalidDimensionException(\count($a) . 'x' . \count($b));
        }

        $mp = [];
        for ($i = 0; $i < $size; ++$i) {
            $mp[$b[$i]] = $i;
        }

        for ($i = 0; $i < $size; ++$i) {
            $b[$i] = $mp[$a[$i]];
        }

        $bPos = [];
        for ($i = 0; $i < $size; ++$i) {
            $bPos[$i] = [$b[$i], $i];
        }

        usort($bPos, function ($e1, $e2) {
            return $e1[0] <=> $e2[0];
        });

        $vis = array_fill(0, $size, false);
        $ans = 0;

        for ($i = 0; $i < $size; ++$i) {
            if ($vis[$i] || $bPos[$i][1] === $i) {
                continue;
            }

            $cycleSize = 0;
            $j         = $i;

            while (!$vis[$j]) {
                $vis[$j] = true;
                $j       = $bPos[$j][1];

                ++$cycleSize;
            }

            $ans += $cycleSize - 1;
        }

        return $ans;
    }
}
