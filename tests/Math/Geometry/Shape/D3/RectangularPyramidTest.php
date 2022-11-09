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

namespace phpOMS\tests\Math\Geometry\Shape\D3;

use phpOMS\Math\Geometry\Shape\D3\RectangularPyramid;

/**
 * @testdox phpOMS\tests\Math\Geometry\Shape\D3\RectangularPyramidTest: Rectangular pyramid shape
 *
 * @internal
 */
final class RectangularPyramidTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The volume can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\RectangularPyramid
     * @group framework
     */
    public function testVolume() : void
    {
        self::assertEqualsWithDelta(8, RectangularPyramid::getVolume(2, 3, 4), 0.01);
    }

    /**
     * @testdox The surface can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\RectangularPyramid
     * @group framework
     */
    public function testSurface() : void
    {
        self::assertEqualsWithDelta(26.91, RectangularPyramid::getSurface(2, 3, 4), 0.01);
    }

    /**
     * @testdox The lateral surface can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\RectangularPyramid
     * @group framework
     */
    public function testLateralSurface() : void
    {
        self::assertEqualsWithDelta(20.91, RectangularPyramid::getLateralSurface(2, 3, 4), 0.01);
    }
}
