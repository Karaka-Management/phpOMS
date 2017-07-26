<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Math\Geometry\ConvexHull;

/**
 * Andrew's monotone chain convex hull algorithm class.
 *
 * @category   Framework
 * @package    phpOMS\Utils\TaskSchedule
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 *
 * @todo       : implement vertice class or use vertice class used by graphs? May be usefull in order to give vertices IDs!
 */
final class MonotoneChain
{
    /**
     * Create convex hull
     *
     * @param array $points Points (Point Cloud)
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function createConvexHull(array $points) : array
    {
        if (($n = count($points)) > 1) {
            $k = 0;
            $h = [];

            uasort($points, [self::class, 'sort']);

            // Lower hull
            for ($i = 0; $i < $n; ++$i) {
                while ($k >= 2 && self::cross($h[$k - 2], $h[$k - 1], $points[$i]) <= 0) {
                    $k--;
                }

                $h[$k++] = $points[$i];
            }

            // Upper hull
            for ($i = $n - 2, $t = $k + 1; $i >= 0; $i--) {
                while ($k >= $t && self::cross($h[$k - 2], $h[$k - 1], $points[$i]) <= 0) {
                    $k--;
                }

                $h[$k++] = $points[$i];
            }

            if ($k > 1) {
                $h = array_splice($h, $k - 1);
            }

            return $h;
        }

        return $points;
    }

    /**
     * Counter clock wise turn?
     *
     * @param array $a Point a
     * @param array $b Point b
     * @param array $c Point c
     *
     * @return float
     *
     * @since  1.0.0
     */
    private static function cross(array $a, array $b, array $c) : float
    {
        return ($b['x'] - $a['x']) * ($c['y'] - $a['y']) - ($b['y'] - $a['y']) * ($c['x'] - $a['x']);
    }

    /**
     * Sort by x coordinate then by z coordinate
     *
     * @param array $a Point a
     * @param array $b Point b
     *
     * @return float
     *
     * @since  1.0.0
     */
    private static function sort(array $a, array $b) : float
    {
        return $a['x'] === $b['x'] ? $a['y'] - $b['y'] : $a['x'] - $b['x'];
    }
}