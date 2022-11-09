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

use phpOMS\Math\Geometry\Shape\D3\Cone;

/**
 * @testdox phpOMS\tests\Math\Geometry\Shape\D3\ConeTest: Cone shape
 *
 * @internal
 */
final class ConeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The volume can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\Cone
     * @group framework
     */
    public function testVolume() : void
    {
        self::assertEqualsWithDelta(12.57, Cone::getVolume(2, 3), 0.01);
    }

    /**
     * @testdox The surface can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\Cone
     * @group framework
     */
    public function testSurface() : void
    {
        self::assertEqualsWithDelta(35.22, Cone::getSurface(2, 3), 0.01);
    }

    /**
     * @testdox The slant height can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\Cone
     * @group framework
     */
    public function testSlantHeight() : void
    {
        self::assertEqualsWithDelta(3.61, Cone::getSlantHeight(2, 3), 0.01);
    }

    /**
     * @testdox The height can be calculated with the volume
     * @covers phpOMS\Math\Geometry\Shape\D3\Cone
     * @group framework
     */
    public function testHeightFromVolume() : void
    {
        self::assertEqualsWithDelta(3, Cone::getHeightFromVolume(12.57, 2), 0.01);
    }
}
