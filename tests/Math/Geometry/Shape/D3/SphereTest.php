<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Geometry\Shape\D3;

use phpOMS\Math\Geometry\Shape\D3\Sphere;

/**
 * @testdox phpOMS\tests\Math\Geometry\Shape\D3\SphereTest: Sphere shape
 *
 * @internal
 */
final class SphereTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The volume can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\Sphere
     * @group framework
     */
    public function testVolume() : void
    {
        $sphere = new Sphere(3);
        self::assertEqualsWithDelta(113.1, $sphere->getVolume(), 0.1);
    }

    /**
     * @testdox The surface can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\Sphere
     * @group framework
     */
    public function testSurface() : void
    {
        $sphere = new Sphere(3);
        self::assertEqualsWithDelta(113.1, $sphere->getSurface(), 0.1);
    }

    /**
     * @testdox The distance on a sphere can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D3\Sphere
     * @group framework
     */
    public function testDistanceOnSphere() : void
    {
        self::assertEqualsWithDelta(422740, Sphere::distance2PointsOnSphere(32.9697, -96.80322, 29.46786, -98.53506), 50);
    }

    /**
     * @testdox The sphere can be created by its radius
     * @covers phpOMS\Math\Geometry\Shape\D3\Sphere
     * @group framework
     */
    public function testGetSphereByRadius() : void
    {
        $sphere = Sphere::byRadius(3);
        self::assertEqualsWithDelta(3, $sphere->getRadius(), 0.1);
    }

    /**
     * @testdox The sphere can be created by its volume
     * @covers phpOMS\Math\Geometry\Shape\D3\Sphere
     * @group framework
     */
    public function testGetSphereByVolume() : void
    {
        $sphere = Sphere::byVolume(4);
        self::assertEqualsWithDelta(4, $sphere->getVolume(), 0.1);
    }

    /**
     * @testdox The sphere can be created by its surface
     * @covers phpOMS\Math\Geometry\Shape\D3\Sphere
     * @group framework
     */
    public function testGetSphereBySurface() : void
    {
        $sphere = Sphere::bySurface(5);
        self::assertEqualsWithDelta(5, $sphere->getSurface(), 0.1);
    }
}
