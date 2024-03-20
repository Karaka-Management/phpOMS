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

use phpOMS\Math\Geometry\Shape\D3\RectangularPyramid;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Geometry\Shape\D3\RectangularPyramid::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Geometry\Shape\D3\RectangularPyramidTest: Rectangular pyramid shape')]
final class RectangularPyramidTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The volume can be calculated')]
    public function testVolume() : void
    {
        self::assertEqualsWithDelta(8, RectangularPyramid::getVolume(2, 3, 4), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The surface can be calculated')]
    public function testSurface() : void
    {
        self::assertEqualsWithDelta(26.91, RectangularPyramid::getSurface(2, 3, 4), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The lateral surface can be calculated')]
    public function testLateralSurface() : void
    {
        self::assertEqualsWithDelta(20.91, RectangularPyramid::getLateralSurface(2, 3, 4), 0.01);
    }
}
