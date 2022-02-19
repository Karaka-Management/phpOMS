<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Geometry\Shape\D3;

use phpOMS\Math\Geometry\Shape\D3\Cylinder;

/**
 * @testdox phpOMS\tests\Math\Geometry\Shape\D3\CylinderTest: Cylinder shape
 *
 * @internal
 */
final class CylinderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The volume can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\Cylinder
     * @group framework
     */
    public function testVolume() : void
    {
        self::assertEqualsWithDelta(37.7, Cylinder::getVolume(2, 3), 0.01);
    }

    /**
     * @testdox The surface can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\Cylinder
     * @group framework
     */
    public function testSurface() : void
    {
        self::assertEqualsWithDelta(62.83, Cylinder::getSurface(2, 3), 0.01);
    }

    /**
     * @testdox The lateral surface can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\Cylinder
     * @group framework
     */
    public function testLateralSurface() : void
    {
        self::assertEqualsWithDelta(37.7, Cylinder::getLateralSurface(2, 3), 0.01);
    }
}
