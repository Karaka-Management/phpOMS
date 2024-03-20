<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Algorithm\Clustering;

use phpOMS\Algorithm\Clustering\Point;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Algorithm\Clustering\Point::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\Clustering\PointTest: Default point in a cluster')]
final class PointTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The point has the expected default values after initialization')]
    public function testDefault() : void
    {
        $point = new Point([3.0, 2.0], 'abc');

        self::assertEquals([3.0, 2.0], $point->getCoordinates());
        self::assertEquals(3.0, $point->getCoordinate(0));
        self::assertEquals(2.0, $point->getCoordinate(1));
        self::assertEquals(0, $point->group);
        self::assertEquals('abc', $point->name);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Coordinates of a point can be set and returned')]
    public function testCoordinateInputOutput() : void
    {
        $point = new Point([3.0, 2.0], 'abc');

        $point->setCoordinate(0, 4.0);
        $point->setCoordinate(1, 1.0);

        self::assertEquals([4.0, 1.0], $point->getCoordinates());
        self::assertEquals(4.0, $point->getCoordinate(0));
        self::assertEquals(1.0, $point->getCoordinate(1));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The group/cluster of a point can be set and returned')]
    public function testGroupInputOutput() : void
    {
        $point = new Point([3.0, 2.0], 'abc');

        $point->group = 2;
        self::assertEquals(2, $point->group);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The name of a point can be set and returned')]
    public function testNameInputOutput() : void
    {
        $point = new Point([3.0, 2.0], 'abc');

        $point->name = 'xyz';
        self::assertEquals('xyz', $point->name);
    }
}
