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

namespace phpOMS\tests\Math\Geometry\Shape\D2;

use phpOMS\Math\Geometry\Shape\D2\Triangle;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Geometry\Shape\D2\Triangle::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Geometry\Shape\D2\TriangleTest: Triangle shape')]
final class TriangleTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The surface can be calculated')]
    public function testSurface() : void
    {
        self::assertEqualsWithDelta(3, Triangle::getSurface(2, 3), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The perimeter can be calculated')]
    public function testPerimeter() : void
    {
        self::assertEqualsWithDelta(9, Triangle::getPerimeter(2, 3, 4), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The height can be calculated')]
    public function testHeight() : void
    {
        self::assertEqualsWithDelta(3, Triangle::getHeight(3, 2), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The hypotenuse can be calculated')]
    public function testHypot() : void
    {
        self::assertEqualsWithDelta(5, Triangle::getHypot(4, 3), 0.001);
    }
}
