<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Geometry\Shape\D2;

use phpOMS\Math\Geometry\Shape\D2\Ellipse;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Geometry\Shape\D2\Ellipse::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Geometry\Shape\D2\EllipseTest: Ellipse shape')]
final class EllipseTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The surface can be calculated')]
    public function testSurface() : void
    {
        self::assertEqualsWithDelta(6.28, Ellipse::getSurface(2, 1), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The perimeter can be calculated')]
    public function testPerimeter() : void
    {
        self::assertEqualsWithDelta(9.69, Ellipse::getPerimeter(2, 1), 0.01);
    }
}
