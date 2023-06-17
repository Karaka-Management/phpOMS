<?php
/**
 * Jingga
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

use phpOMS\Math\Geometry\Shape\D2\Trapezoid;

/**
 * @testdox phpOMS\tests\Math\Geometry\Shape\D2\TrapezoidTest: Trapezoid shape
 *
 * @internal
 */
final class TrapezoidTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The surface can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Trapezoid
     * @group framework
     */
    public function testSurface() : void
    {
        self::assertEqualsWithDelta(10, Trapezoid::getSurface(2, 3, 4), 0.001);
    }

    /**
     * @testdox The perimeter can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Trapezoid
     * @group framework
     */
    public function testPerimeter() : void
    {
        self::assertEqualsWithDelta(14, Trapezoid::getPerimeter(2, 3, 4, 5), 0.001);
    }

    /**
     * @testdox The height can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Trapezoid
     * @group framework
     */
    public function testHeight() : void
    {
        self::assertEqualsWithDelta(4, Trapezoid::getHeight(10, 2, 3), 0.001);
    }

    /**
     * @testdox The side lengths can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Trapezoid
     * @group framework
     */
    public function testSideLength() : void
    {
        self::assertEqualsWithDelta(2, Trapezoid::getA(10, 4, 3), 0.001);
        self::assertEqualsWithDelta(3, Trapezoid::getB(10, 4, 2), 0.001);
        self::assertEqualsWithDelta(4, Trapezoid::getC(14, 2, 3, 5), 0.001);
        self::assertEqualsWithDelta(5, Trapezoid::getD(14, 2, 3, 4), 0.001);
    }
}
