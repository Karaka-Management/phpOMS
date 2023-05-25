<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Geometry\ConvexHull
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Geometry\ConvexHull;

/**
 * Andrew's monotone chain convex hull algorithm class.
 *
 * @package phpOMS\Math\Geometry\ConvexHull
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class MonotoneChain
{
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
     * Create convex hull
     *
     * @param array<int, array{x:int|float, y:int|float}> $points Points (Point Cloud)
     *
     * @return array<int, array{x:int|float, y:int|float}>
     *
     * @since 1.0.0
     */
    public static function createConvexHull(array $points) : array
    {
        if (($n = \count($points)) < 2) {
            return $points;
        }

        \uasort($points, [self::class, 'sort']);

        $k      = 0;
        $result = [];

        // Lower hull
        for ($i = 0; $i < $n; ++$i) {
            while ($k >= 2
                && ($result[$k - 1]['x'] - $result[$k - 2]['x']) * ($points[$i]['y'] - $result[$k - 2]['y'])
                    - ($result[$k - 1]['y'] - $result[$k - 2]['y']) * ($points[$i]['x'] - $result[$k - 2]['x']
                ) <= 0
            ) {
                --$k;
            }

            $result[$k++] = $points[$i];
        }

        // Upper hull
        for ($i = $n - 2, $t = $k + 1; $i >= 0; --$i) {
            while ($k >= $t
                && ($result[$k - 1]['x'] - $result[$k - 2]['x']) * ($points[$i]['y'] - $result[$k - 2]['y'])
                    - ($result[$k - 1]['y'] - $result[$k - 2]['y']) * ($points[$i]['x'] - $result[$k - 2]['x']
                ) <= 0
            ) {
                --$k;
            }

            $result[$k++] = $points[$i];
        }

        \ksort($result);

        /** @return array<int, array{x:int|float, y:int|float}> */
        return \array_slice($result, 0, $k - 1);
    }

    /**
     * Sort by x coordinate then by z coordinate
     *
     * @param array{x:int|float, y:int|float} $a Point a
     * @param array{x:int|float, y:int|float} $b Point b
     *
     * @return float
     *
     * @since 1.0.0
     */
    private static function sort(array $a, array $b) : float
    {
        return $a['x'] === $b['x'] ? $a['y'] - $b['y'] : $a['x'] - $b['x'];
    }
}
