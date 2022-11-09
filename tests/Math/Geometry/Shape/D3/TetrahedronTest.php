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

use phpOMS\Math\Geometry\Shape\D3\Tetrahedron;

/**
 * @testdox phpOMS\tests\Math\Geometry\Shape\D3\TetrahedronTest: Tetrahedron shape
 *
 * @internal
 */
final class TetrahedronTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The volume can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\Tetrahedron
     * @group framework
     */
    public function testVolume() : void
    {
        self::assertEqualsWithDelta(3.18, Tetrahedron::getVolume(3), 0.01);
    }

    /**
     * @testdox The surface can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\Tetrahedron
     * @group framework
     */
    public function testSurface() : void
    {
        self::assertEqualsWithDelta(15.59, Tetrahedron::getSurface(3), 0.01);
    }

    /**
     * @testdox The face area can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\Tetrahedron
     * @group framework
     */
    public function testFaceArea() : void
    {
        self::assertEqualsWithDelta(3.9, Tetrahedron::getFaceArea(3), 0.01);
    }
}
