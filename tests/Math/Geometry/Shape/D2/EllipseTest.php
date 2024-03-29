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

use phpOMS\Math\Geometry\Shape\D2\Ellipse;

/**
 * @testdox phpOMS\tests\Math\Geometry\Shape\D2\EllipseTest: Ellipse shape
 *
 * @internal
 */
final class EllipseTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The surface can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Ellipse
     * @group framework
     */
    public function testSurface() : void
    {
        self::assertEqualsWithDelta(6.28, Ellipse::getSurface(2, 1), 0.01);
    }

    /**
     * @testdox The perimeter can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Ellipse
     * @group framework
     */
    public function testPerimeter() : void
    {
        self::assertEqualsWithDelta(9.69, Ellipse::getPerimeter(2, 1), 0.01);
    }
}
