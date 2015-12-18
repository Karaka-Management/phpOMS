<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Math\Algebra;

class PointPolygonIntersection
{
    const EPSILON = 1E-6;

    private function __construct()
    {
    }

    public static function pointInPolygon(array $point, array $vertices) : \int
    {
        $length = count($vertices);

        // Polygon has to start and end with same point
        if ($vertices[0]['x'] !== $vertices[$length - 1]['x'] || $vertices[0]['y'] !== $vertices[$length - 1]['y']) {
            $vertices[] = $vertices[0];
        }

        // On vertex?
        if (self::isOnVertex($point, $vertices)) {
            return 0;
        }

        // Inside or ontop?
        $countIntersect  = 0;
        $vertices_count = count($vertices);

        // todo: return based on highest possibility not by first match
        for ($i = 1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i - 1];
            $vertex2 = $vertices[$i];

            if (abs($vertex1['y'] - $vertex2['y']) < self::EPSILON && abs($vertex1['y'] - $point['y']) < self::EPSILON && $point['x'] > min($vertex1['x'], $vertex2['x']) && $point['x'] < max($vertex1['x'], $vertex2['x'])) {
                return 0; // boundary
            }

            if ($point['y'] > min($vertex1['y'], $vertex2['y']) && $point['y'] <= max($vertex1['y'], $vertex2['y']) && $point['x'] <= max($vertex1['x'], $vertex2['x']) && abs($vertex1['y'] - $vertex2['y']) >= self::EPSILON) {
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];

                if (abs($xinters - $point['x']) < self::EPSILON) {
                    return 0; // boundary
                }

                if (abs($vertex1['x'] - $vertex2['x']) < self::EPSILON || $point['x'] < $xinters) {
                    $countIntersect++;
                }
            }
        }

        if ($countIntersect % 2 != 0) {
            return -1;
        }

        return 1;
    }

    private static function isOnVertex($point, $vertices)
    {
        foreach ($vertices as $vertex) {
            if (abs($point['x'] - $vertex['x']) < self::EPSILON && abs($point['y'] - $vertex['y']) < self::EPSILON) {
                return true;
            }
        }

        return false;
    }
}

