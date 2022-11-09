<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Geometry\Shape\D2;

use phpOMS\Math\Geometry\Shape\D2\Polygon;

/**
 * @testdox phpOMS\tests\Math\Geometry\Shape\D2\PolygonTest: Polygon shape
 *
 * @internal
 */
final class PolygonTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The location of a point can be checked relative to a polygon
     * @covers phpOMS\Math\Geometry\Shape\D2\Polygon
     * @group framework
     */
    public function testPoint() : void
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

    /**
     * @testdox The interior angle can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Polygon
     * @group framework
     */
    public function testInteriorAngle() : void
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
    }

    /**
     * @testdox The exterior angle can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Polygon
     * @group framework
     */
    public function testExteriorAngle() : void
    {
        $polygon = new Polygon([[1, 2], [2, 3], [3, 4]]);
        self::assertEquals(360, $polygon->getExteriorAngleSum());
    }

    /**
     * @testdox The perimeter can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Polygon
     * @group framework
     */
    public function testPerimeter() : void
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
        self::assertEqualsWithDelta(9.6568, $polygon->getPerimeter(), 0.1);
    }

    /**
     * @testdox The area can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Polygon
     * @group framework
     */
    public function testArea() : void
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

    /**
     * @testdox The barycenter can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Polygon
     * @group framework
     */
    public function testBarycenter() : void
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
        self::assertEqualsWithDelta(['x' => 3.5, 'y' => 1.5], $polygon->getBarycenter(), 0.5);
    }

    /**
     * @testdox The regular area can be calculated with the side length
     * @covers phpOMS\Math\Geometry\Shape\D2\Polygon
     * @group framework
     */
    public function testRegularAreaByLength() : void
    {
        self::assertEqualsWithDelta(3 * 3, Polygon::getRegularAreaByLength(3.0, 4), 0.01);
    }

    /**
     * @testdox The regular area can be calculated with the radius
     * @covers phpOMS\Math\Geometry\Shape\D2\Polygon
     * @group framework
     */
    public function testRegularAreaByRadius() : void
    {
        self::assertEqualsWithDelta(3 * 3 , Polygon::getRegularAreaByRadius(1.5, 4), 0.01);
    }
}
