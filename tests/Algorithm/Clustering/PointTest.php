<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Clustering;

use phpOMS\Algorithm\Clustering\Point;

/**
 * @testdox phpOMS\Algorithm\Clustering\Point: Test the point for the clustering implementation
 *
 * @internal
 */
class PointTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $point = new Point([3.0, 2.0], 'abc');

        self::assertEquals([3.0, 2.0], $point->getCoordinates());
        self::assertEquals(3.0, $point->getCoordinate(0));
        self::assertEquals(2.0, $point->getCoordinate(1));
        self::assertEquals(0, $point->getGroup());
        self::assertEquals('abc', $point->getName());
    }

    public function testSetGet() : void
    {
        $point = new Point([3.0, 2.0], 'abc');

        $point->setCoordinate(0, 4.0);
        $point->setCoordinate(1, 1.0);

        self::assertEquals([4.0, 1.0], $point->getCoordinates());
        self::assertEquals(4.0, $point->getCoordinate(0));
        self::assertEquals(1.0, $point->getCoordinate(1));

        $point->setGroup(2);
        self::assertEquals(2, $point->getGroup());
    }
}
