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

use phpOMS\Math\Geometry\Shape\D3\Cone;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Geometry\Shape\D3\Cone::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Geometry\Shape\D3\ConeTest: Cone shape')]
final class ConeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The volume can be calculated')]
    public function testVolume() : void
    {
        self::assertEqualsWithDelta(12.57, Cone::getVolume(2, 3), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The surface can be calculated')]
    public function testSurface() : void
    {
        self::assertEqualsWithDelta(35.22, Cone::getSurface(2, 3), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The slant height can be calculated')]
    public function testSlantHeight() : void
    {
        self::assertEqualsWithDelta(3.61, Cone::getSlantHeight(2, 3), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The height can be calculated with the volume')]
    public function testHeightFromVolume() : void
    {
        self::assertEqualsWithDelta(3, Cone::getHeightFromVolume(12.57, 2), 0.01);
    }
}
