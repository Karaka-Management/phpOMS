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

use phpOMS\Math\Geometry\Shape\D2\Triangle;

/**
 * @testdox phpOMS\tests\Math\Geometry\Shape\D2\TriangleTest: Triangle shape
 *
 * @internal
 */
class TriangleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The surface can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Triangle
     * @group framework
     */
    public function testSurface() : void
    {
        self::assertEqualsWithDelta(3, Triangle::getSurface(2, 3), 0.001);
    }

    /**
     * @testdox The perimeter can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Triangle
     * @group framework
     */
    public function testPerimeter() : void
    {
        self::assertEqualsWithDelta(9, Triangle::getPerimeter(2, 3, 4), 0.001);
    }

    /**
     * @testdox The height can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Triangle
     * @group framework
     */
    public function testHeight() : void
    {
        self::assertEqualsWithDelta(3, Triangle::getHeight(3, 2), 0.001);
    }

    /**
     * @testdox The hypotenuse can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Triangle
     * @group framework
     */
    public function testHypot() : void
    {
        self::assertEqualsWithDelta(5, Triangle::getHypot(4, 3), 0.001);
    }
}
