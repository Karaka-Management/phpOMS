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

use phpOMS\Math\Geometry\Shape\D3\Prism;

/**
 * @testdox phpOMS\tests\Math\Geometry\Shape\D3\PrismTest: Prism shape
 *
 * @internal
 */
final class PrismTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The volume can be calculated with the length
     * @covers phpOMS\Math\Geometry\Shape\D3\Prism
     * @group framework
     */
    public function testVolumeByLength() : void
    {
        self::assertEqualsWithDelta(3 * 3 * 12, Prism::getVolumeRegularLength(3, 4, 12), 0.01);
    }

    /**
     * @testdox The volume can be calculated with the radius
     * @covers phpOMS\Math\Geometry\Shape\D3\Prism
     * @group framework
     */
    public function testVolumeByRadius() : void
    {
        self::assertEqualsWithDelta(3 * 3 * 12, Prism::getVolumeRegularRadius(1.5, 4, 12), 0.01);
    }

    /**
     * @testdox The surface can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\Prism
     * @group framework
     */
    public function testSurface() : void
    {
        self::assertEqualsWithDelta(3 * 3 * 2 + 3 * 12 * 4, Prism::getSurfaceRegularLength(3, 4, 12), 0.01);
    }
}
