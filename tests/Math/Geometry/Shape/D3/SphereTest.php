<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Geometry\Shape\D3;

use phpOMS\Math\Geometry\Shape\D3\Sphere;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Geometry\Shape\D3\Sphere::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Geometry\Shape\D3\SphereTest: Sphere shape')]
final class SphereTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The volume can be calculated')]
    public function testVolume() : void
    {
        $sphere = new Sphere(3);
        self::assertEqualsWithDelta(113.1, $sphere->getVolume(), 0.1);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The surface can be calculated')]
    public function testSurface() : void
    {
        $sphere = new Sphere(3);
        self::assertEqualsWithDelta(113.1, $sphere->getSurface(), 0.1);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The distance on a sphere can be calculated')]
    public function testDistanceOnSphere() : void
    {
        self::assertEqualsWithDelta(422740, Sphere::distance2PointsOnSphere(32.9697, -96.80322, 29.46786, -98.53506), 50);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The sphere can be created by its radius')]
    public function testGetSphereByRadius() : void
    {
        $sphere = Sphere::byRadius(3);
        self::assertEqualsWithDelta(3, $sphere->getRadius(), 0.1);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The sphere can be created by its volume')]
    public function testGetSphereByVolume() : void
    {
        $sphere = Sphere::byVolume(4);
        self::assertEqualsWithDelta(4, $sphere->getVolume(), 0.1);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The sphere can be created by its surface')]
    public function testGetSphereBySurface() : void
    {
        $sphere = Sphere::bySurface(5);
        self::assertEqualsWithDelta(5, $sphere->getSurface(), 0.1);
    }
}
