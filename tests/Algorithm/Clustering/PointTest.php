<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Algorithm\Clustering;

use phpOMS\Algorithm\Clustering\Point;

/**
 * @testdox phpOMS\tests\Algorithm\Clustering\PointTest: Default point in a cluster
 *
 * @internal
 */
final class PointTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The point has the expected default values after initialization
     * @covers phpOMS\Algorithm\Clustering\Point
     * @group framework
     */
    public function testDefault() : void
    {
        $point = new Point([3.0, 2.0], 'abc');

        self::assertEquals([3.0, 2.0], $point->getCoordinates());
        self::assertEquals(3.0, $point->getCoordinate(0));
        self::assertEquals(2.0, $point->getCoordinate(1));
        self::assertEquals(0, $point->group);
        self::assertEquals('abc', $point->name);
    }

    /**
     * @testdox Coordinates of a point can be set and returned
     * @covers phpOMS\Algorithm\Clustering\Point
     * @group framework
     */
    public function testCoordinateInputOutput() : void
    {
        $point = new Point([3.0, 2.0], 'abc');

        $point->setCoordinate(0, 4.0);
        $point->setCoordinate(1, 1.0);

        self::assertEquals([4.0, 1.0], $point->getCoordinates());
        self::assertEquals(4.0, $point->getCoordinate(0));
        self::assertEquals(1.0, $point->getCoordinate(1));
    }

    /**
     * @testdox The group/cluster of a point can be set and returned
     * @covers phpOMS\Algorithm\Clustering\Point
     * @group framework
     */
    public function testGroupInputOutput() : void
    {
        $point = new Point([3.0, 2.0], 'abc');

        $point->group = 2;
        self::assertEquals(2, $point->group);
    }

    /**
     * @testdox The name of a point can be set and returned
     * @covers phpOMS\Algorithm\Clustering\Point
     * @group framework
     */
    public function testNameInputOutput() : void
    {
        $point = new Point([3.0, 2.0], 'abc');

        $point->name = 'xyz';
        self::assertEquals('xyz', $point->name);
    }
}
