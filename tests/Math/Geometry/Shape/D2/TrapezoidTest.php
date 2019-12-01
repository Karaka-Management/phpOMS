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

namespace phpOMS\tests\Math\Geometry\Shape\D2;

use phpOMS\Math\Geometry\Shape\D2\Trapezoid;

/**
 * @internal
 */
class TrapezoidTest extends \PHPUnit\Framework\TestCase
{
    public function testSurface() : void
    {
        self::assertEqualsWithDelta(10, Trapezoid::getSurface(2, 3, 4), 0.001);
    }

    public function testPerimeter() : void
    {
        self::assertEqualsWithDelta(14, Trapezoid::getPerimeter(2, 3, 4, 5), 0.001);
    }

    public function testHeight() : void
    {
        self::assertEqualsWithDelta(4, Trapezoid::getHeight(10, 2, 3), 0.001);
    }

    public function testSideLength() : void
    {
        self::assertEqualsWithDelta(2, Trapezoid::getA(10, 4, 3), 0.001);
        self::assertEqualsWithDelta(3, Trapezoid::getB(10, 4, 2), 0.001);
        self::assertEqualsWithDelta(4, Trapezoid::getC(14, 2, 3, 5), 0.001);
        self::assertEqualsWithDelta(5, Trapezoid::getD(14, 2, 3, 4), 0.001);
    }
}
