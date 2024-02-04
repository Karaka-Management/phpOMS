<?php
/**
 * Jingga
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
final class GrahamScan
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
        if (($n = \count($points)) < 3) {
            return $points;
        }

        $min    = 1;
        $points = \array_merge([null], $points);

        for ($i = 2; $i < $n; ++$i) {
            if ($points[$i]['y'] < $points[$min]['y']
                || ($points[$i]['y'] === $points[$min]['y'] && $points[$i]['x'] < $points[$min]['x'])
            ) {
                $min = $i;
            }
        }

        $temp         = $points[1];
        $points[1]    = $points[$min];
        $points[$min] = $temp;

        $c = $points[1];

        /** @var array<int, array{x:int|float, y:int|float}> $subpoints */
        $subpoints = \array_slice($points, 2, $n);
        \usort($subpoints, function (array $a, array $b) use ($c) : int {
            return \atan2($a['y'] - $c['y'], $a['x'] - $c['x']) <=> \atan2($b['y'] - $c['y'],  $b['x'] - $c['x']);
        });

        /** @var array<int, array{x:int|float, y:int|float}> $points */
        $points    = \array_merge([$points[0], $points[1]], $subpoints);
        $points[0] = $points[$n];

        $size = 1;
        for ($i = 2; $i <= $n; ++$i) {
            while (self::ccw($points[$size - 1], $points[$size], $points[$i]) <= 0) {
                if ($size > 1) {
                    --$size;
                } elseif ($i === $n) {
                    break;
                } else {
                    ++$i;
                }
            }

            $temp              = $points[$i];
            $points[$size + 1] = $points[$i];
            $points[$i]        = $points[$size + 1];
            ++$size;
        }

        $hull = [];
        for ($i = 1; $i <= $size; ++$i) {
            $hull[] = $points[$i];
        }

        return $hull;
    }

    /**
     * Counterclockwise rotation
     *
     * @param array{x:int|float, y:int|float} $a Vector
     * @param array{x:int|float, y:int|float} $b Vector
     * @param array{x:int|float, y:int|float} $c Vector
     *
     * @return int|float
     *
     * @since 1.0.0
     */
    public static function ccw(array $a, array $b, array $c) : int|float
    {
        return (($b['x'] - $a['x']) * ($c['y'] - $a['y']) - ($b['y'] - $a['y']) * ($c['x'] - $a['x']));
    }
}
