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

namespace phpOMS\tests\Math\Geometry\Shape\D3;

use phpOMS\Math\Geometry\Shape\D3\Sphere;

/**
 * @internal
 */
class SphereTest extends \PHPUnit\Framework\TestCase
{
    public function testVolume() : void
    {
        $sphere = new Sphere(3);
        self::assertEqualsWithDelta(113.1, $sphere->getVolume(), 0.1);
    }

    public function testSurface() : void
    {
        $sphere = new Sphere(3);
        self::assertEqualsWithDelta(113.1, $sphere->getSurface(), 0.1);
    }

    public function testDistanceOnSphere() : void
    {
        self::assertEqualsWithDelta(422740, Sphere::distance2PointsOnSphere(32.9697, -96.80322, 29.46786, -98.53506), 50);
    }

    public function testGetSphereByRadius() : void
    {
        $sphere = Sphere::byRadius(3);
        self::assertEqualsWithDelta(3, $sphere->getRadius(), 0.1);
    }

    public function testGetSphereByVolume() : void
    {
        $sphere = Sphere::byVolume(4);
        self::assertEqualsWithDelta(4, $sphere->getVolume(), 0.1);
    }

    public function testGetSphereBySurface() : void
    {
        $sphere = Sphere::bySurface(5);
        self::assertEqualsWithDelta(5, $sphere->getSurface(), 0.1);
    }
}
