<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Geometry\Shape\D2;

use phpOMS\Math\Geometry\Shape\D2\Circle;

/**
 * @testdox phpOMS\tests\Math\Geometry\Shape\D2\CircleTest: Circle shape
 *
 * @internal
 */
final class CircleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The surface can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Circle
     * @group framework
     */
    public function testSurface() : void
    {
        self::assertEqualsWithDelta(12.57, Circle::getSurface(2), 0.01);
    }

    /**
     * @testdox The perimeter can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Circle
     * @group framework
     */
    public function testPerimeter() : void
    {
        self::assertEqualsWithDelta(12.57, Circle::getPerimeter(2), 0.01);
    }

    /**
     * @testdox The radius can be calculated with the surface
     * @covers phpOMS\Math\Geometry\Shape\D2\Circle
     * @group framework
     */
    public function testRadiusBySurface() : void
    {
        self::assertEqualsWithDelta(2.0, Circle::getRadiusBySurface(Circle::getSurface(2)), 0.001);
    }

    /**
     * @testdox The radius can be calculated with the perimeter
     * @covers phpOMS\Math\Geometry\Shape\D2\Circle
     * @group framework
     */
    public function testRadiusByPerimeter() : void
    {
        self::assertEqualsWithDelta(2.0, Circle::getRadiusByPerimeter(Circle::getPerimeter(2)), 0.001);
    }
}
