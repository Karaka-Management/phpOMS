<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Geometry\Shape\D2
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Geometry\Shape\D2;

/**
 * Polygon class.
 *
 * @package phpOMS\Math\Geometry\Shape\D2
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Polygon implements D2ShapeInterface
{
    /**
     * Epsilon for float comparison.
     *
     * @var float
     * @since 1.0.0
     */
    public const EPSILON = 4.88e-04;

    /**
     * Coordinates.
     *
     * These coordinates define the polygon
     *
     * @var array<int, array{x:int|float, y:int|float}>
     * @since 1.0.0
     */
    private array $coord = [];

    /**
     * Constructor.
     *
     * @param array<int, array{x:int|float, y:int|float}> $coord 2 Dimensional coordinate array where the indices are x and y
     *
     * @example Polygon([['x' => 1, 'y' => 2], ['x' => ...], ...])
     *
     * @since 1.0.0
     */
    public function __construct(array $coord)
    {
        $this->coord = $coord;
    }

    /**
     * Point polygon relative position
     *
     * @param array{x:int|float, y:int|float} $point Point location
     *
     * @return int -1 inside polygon 0 on vertice 1 outside
     *
     * @since 1.0.0
     */
    public function pointInPolygon(array $point) : int
    {
        $coord   = $this->coord;
        $coord[] = $this->coord[0];

        return self::isPointInPolygon($point, $coord);
    }

    /**
     * Point polygon relative position
     *
     * @param array{x:int|float, y:int|float}             $point   Point location
     * @param array<int, array{x:int|float, y:int|float}> $polygon Polygon definition
     *
     * @return int -1 inside polygon 0 on vertice 1 outside
     *
     * @link http://erich.realtimerendering.com/ptinpoly/
     * @since  1.0.0
     */
    public static function isPointInPolygon(array $point, array $polygon) : int
    {
        $length = \count($polygon);

        // Polygon has to start and end with same point
        if ($polygon[0]['x'] !== $polygon[$length - 1]['x'] || $polygon[0]['y'] !== $polygon[$length - 1]['y']) {
            $polygon[] = $polygon[0];
        }

        // On vertex?
        if (self::isOnVertex($point, $polygon)) {
            return 0;
        }

        // Inside or ontop?
        $countIntersect = 0;
        $polygonCount   = \count($polygon);

        for ($i = 1; $i < $polygonCount; ++$i) {
            $vertex1 = $polygon[$i - 1];
            $vertex2 = $polygon[$i];

            if (\abs($vertex1['y'] - $vertex2['y']) < self::EPSILON
                && \abs($vertex1['y'] - $point['y']) < self::EPSILON
                && $point['x'] > \min($vertex1['x'], $vertex2['x'])
                && $point['x'] < \max($vertex1['x'], $vertex2['x'])
            ) {
                return 0; // boundary
            }

            if ($point['y'] > \min($vertex1['y'], $vertex2['y'])
                && $point['y'] <= \max($vertex1['y'], $vertex2['y'])
                && $point['x'] <= \max($vertex1['x'], $vertex2['x'])
                && \abs($vertex1['y'] - $vertex2['y']) >= self::EPSILON
            ) {
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];

                if (\abs($xinters - $point['x']) < self::EPSILON) {
                    return 0; // boundary
                }

                if (\abs($vertex1['x'] - $vertex2['x']) < self::EPSILON || $point['x'] < $xinters) {
                    ++$countIntersect;
                }
            }
        }

        if ($countIntersect % 2 !== 0) {
            return -1;
        }

        return 1;
    }

    /**
     * Is point on vertex?
     *
     * @param array{x:int|float, y:int|float}             $point   Point location
     * @param array<int, array{x:int|float, y:int|float}> $polygon Polygon definition
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private static function isOnVertex(array $point, array $polygon) : bool
    {
        foreach ($polygon as $vertex) {
            if (\abs($point['x'] - $vertex['x']) < self::EPSILON && \abs($point['y'] - $vertex['y']) < self::EPSILON) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get interior angle sum
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getInteriorAngleSum() : int
    {
        return (\count($this->coord) - 2) * 180;
    }

    /**
     * Get exterior angle sum
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getExteriorAngleSum() : int
    {
        return 360;
    }

    /**
     * Get surface area
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getSurface() : float
    {
        return \abs($this->getSignedSurface());
    }

    /**
     * Get signed surface area
     *
     * @return float
     *
     * @since 1.0.0
     */
    private function getSignedSurface() : float
    {
        $count   = \count($this->coord);
        $surface = 0;

        for ($i = 0; $i < $count - 1; ++$i) {
            $surface += $this->coord[$i]['x'] * $this->coord[$i + 1]['y'] - $this->coord[$i + 1]['x'] * $this->coord[$i]['y'];
        }

        $surface += $this->coord[$count - 1]['x'] * $this->coord[0]['y'] - $this->coord[0]['x'] * $this->coord[$count - 1]['y'];

        return $surface / 2;
    }

    /**
     * Get perimeter
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getPerimeter() : float
    {
        $count     = \count($this->coord);
        $perimeter = \sqrt(($this->coord[0]['x'] - $this->coord[$count - 1]['x']) ** 2 + ($this->coord[0]['y'] - $this->coord[$count - 1]['y']) ** 2);

        for ($i = 0; $i < $count - 1; ++$i) {
            $perimeter += \sqrt(($this->coord[$i + 1]['x'] - $this->coord[$i]['x']) ** 2 + ($this->coord[$i + 1]['y'] - $this->coord[$i]['y']) ** 2);
        }

        return $perimeter;
    }

    /**
     * Get barycenter
     *
     * @return array{x:int|float, y:int|float}
     *
     * @since 1.0.0
     */
    public function getBarycenter() : array
    {
        $barycenter = ['x' => 0, 'y' => 0];
        $count      = \count($this->coord);

        for ($i = 0; $i < $count - 1; ++$i) {
            $mult             = ($this->coord[$i]['x'] * $this->coord[$i + 1]['y'] - $this->coord[$i + 1]['x'] * $this->coord[$i]['y']);
            $barycenter['x'] += ($this->coord[$i]['x'] + $this->coord[$i + 1]['x']) * $mult;
            $barycenter['y'] += ($this->coord[$i]['y'] + $this->coord[$i + 1]['y']) * $mult;
        }

        $mult             = ($this->coord[$count - 1]['x'] * $this->coord[0]['y'] - $this->coord[0]['x'] * $this->coord[$count - 1]['y']);
        $barycenter['x'] += ($this->coord[$count - 1]['x'] + $this->coord[0]['x']) * $mult;
        $barycenter['y'] += ($this->coord[$count - 1]['y'] + $this->coord[0]['y']) * $mult;

        $surface = $this->getSignedSurface();

        $barycenter['x'] = 1 / (6 * $surface) * $barycenter['x'];
        $barycenter['y'] = 1 / (6 * $surface) * $barycenter['y'];

        return $barycenter;
    }

    /**
     * Get area by side length
     *
     * @param float $length Side length
     * @param int   $sides  Number of sides
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getRegularAreaByLength(float $length, int $sides) : float
    {
        return $length ** 2 * $sides / (4 * \tan(\M_PI / $sides));
    }

    /**
     * Get area by radius
     *
     * @param float $r     Radius
     * @param int   $sides Number of sides
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getRegularAreaByRadius(float $r, int $sides) : float
    {
        return $r ** 2 * $sides * \tan(\M_PI / $sides);
    }
}
