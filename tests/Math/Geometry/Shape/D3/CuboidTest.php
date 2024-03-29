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

namespace phpOMS\tests\Math\Geometry\Shape\D3;

use phpOMS\Math\Geometry\Shape\D3\Cuboid;

/**
 * @testdox phpOMS\tests\Math\Geometry\Shape\D3\CuboidTest: Cuboid shape
 *
 * @internal
 */
final class CuboidTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The volume can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\Cuboid
     * @group framework
     */
    public function testVolume() : void
    {
        self::assertEqualsWithDelta(200, Cuboid::getVolume(10, 5, 4), 0.001);
    }

    /**
     * @testdox The surface can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\Cuboid
     * @group framework
     */
    public function testSurface() : void
    {
        self::assertEqualsWithDelta(220, Cuboid::getSurface(10, 5, 4), 0.001);
    }
}
