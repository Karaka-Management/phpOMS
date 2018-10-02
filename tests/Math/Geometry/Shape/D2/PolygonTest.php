<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Math\Geometry\Shape\D2;

use phpOMS\Math\Geometry\Shape\D2\Polygon;

class PolygonTest extends \PHPUnit\Framework\TestCase
{
    public function testPoint()
    {
        $polyArray = [
            ['x' => 1, 'y' => 1],
            ['x' => 1, 'y' => 2],
            ['x' => 2, 'y' => 2],
            ['x' => 2, 'y' => 1],
        ];

        $polygon = new Polygon($polyArray);

        self::assertEquals(-1, $polygon->pointInPolygon(['x' => 1.5, 'y' => 1.5]));
        self::assertEquals(1, $polygon->pointInPolygon(['x' => 4.9, 'y' => 1.2]));
        self::assertEquals(-1, $polygon->pointInPolygon(['x' => 1.8, 'y' => 1.1]));

        self::assertEquals(-1, Polygon::isPointInPolygon(['x' => 1.5, 'y' => 1.5], $polyArray));
        self::assertEquals(1, Polygon::isPointInPolygon(['x' => 4.9, 'y' => 1.2], $polyArray));
        self::assertEquals(0, Polygon::isPointInPolygon(['x' => 1, 'y' => 2], $polyArray));
        self::assertEquals(-1, Polygon::isPointInPolygon(['x' => 1.8, 'y' => 1.1], $polyArray));
    }

    public function testAngle()
    {
        $polygon = new Polygon([[1, 2], [2, 3], [3, 4]]);
        self::assertEquals(180, $polygon->getInteriorAngleSum());

        $polygon = new Polygon([[1, 2], [2, 3], [3, 4], [4, 5]]);
        self::assertEquals(360, $polygon->getInteriorAngleSum());

        $polygon = new Polygon([[1, 2], [2, 3], [3, 4], [4, 5], [5, 6]]);
        self::assertEquals(540, $polygon->getInteriorAngleSum());

        $polygon = new Polygon([[1, 2], [2, 3], [3, 4], [4, 5], [5, 6], [6, 7]]);
        self::assertEquals(720, $polygon->getInteriorAngleSum());

        $polygon = new Polygon([[1, 2], [2, 3], [3, 4], [4, 5], [5, 6], [6, 7], [7, 8]]);
        self::assertEquals(900, $polygon->getInteriorAngleSum());

        $polygon = new Polygon([[1, 2], [2, 3], [3, 4], [4, 5], [5, 6], [6, 7], [7, 8], [8, 9]]);
        self::assertEquals(1080, $polygon->getInteriorAngleSum());

        self::assertEquals(360, $polygon->getExteriorAngleSum());
    }

    public function testPerimeter()
    {
        $polygon = new Polygon([
            ['x' => 2, 'y' => 1],
            ['x' => 2, 'y' => 2],
            ['x' => 3, 'y' => 3],
            ['x' => 4, 'y' => 3],
            ['x' => 5, 'y' => 2],
            ['x' => 5, 'y' => 1],
            ['x' => 4, 'y' => 0],
            ['x' => 3, 'y' => 0],
        ]);
        self::assertEquals(9.6568, $polygon->getPerimeter(), '', 0.1);
    }

    public function testArea()
    {
        $polygon = new Polygon([
            ['x' => 2, 'y' => 1],
            ['x' => 2, 'y' => 2],
            ['x' => 3, 'y' => 3],
            ['x' => 4, 'y' => 3],
            ['x' => 5, 'y' => 2],
            ['x' => 5, 'y' => 1],
            ['x' => 4, 'y' => 0],
            ['x' => 3, 'y' => 0],
        ]);
        self::assertEquals(7, $polygon->getSurface());
    }

    public function testBarycenter()
    {
        $polygon = new Polygon([
            ['x' => 2, 'y' => 1],
            ['x' => 2, 'y' => 2],
            ['x' => 3, 'y' => 3],
            ['x' => 4, 'y' => 3],
            ['x' => 5, 'y' => 2],
            ['x' => 5, 'y' => 1],
            ['x' => 4, 'y' => 0],
            ['x' => 3, 'y' => 0],
        ]);
        self::assertEquals(['x' => 3.5, 'y' => 1.5], $polygon->getBarycenter(), '', 0.5);
    }
}
