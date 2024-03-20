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

namespace phpOMS\tests\Math\Geometry\Shape\D2;

use phpOMS\Math\Geometry\Shape\D2\Rectangle;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Geometry\Shape\D2\Rectangle::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Geometry\Shape\D2\RectangleTest: Rectangle shape')]
final class RectangleTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The surface can be calculated')]
    public function testSurface() : void
    {
        self::assertEqualsWithDelta(10, Rectangle::getSurface(5, 2), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The perimeter can be calculated')]
    public function testPerimeter() : void
    {
        self::assertEqualsWithDelta(10, Rectangle::getPerimeter(2, 3), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The diagonal can be calculated')]
    public function testDiagonal() : void
    {
        self::assertEqualsWithDelta(32.7, Rectangle::getDiagonal(30, 13), 0.01);
    }
}
