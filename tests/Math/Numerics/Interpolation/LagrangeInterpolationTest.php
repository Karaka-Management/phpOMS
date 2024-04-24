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

namespace phpOMS\tests\Math\Numerics\Interpolation;

use phpOMS\Math\Numerics\Interpolation\LagrangeInterpolation;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Numerics\Interpolation\LagrangeInterpolation::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Numerics\Interpolation\LagrangeInterpolationTest: Lagrange interpolation')]
final class LagrangeInterpolationTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The lagrange interpolation is correct')]
    public function testInterpolation() : void
    {
        $interpolation = new LagrangeInterpolation([
            ['x' => 0.0, 'y' => 2.0],
            ['x' => 1.0, 'y' => 3.0],
            ['x' => 2.0, 'y' => 12.0],
            ['x' => 5.0, 'y' => 147.0],
        ]);

        self::assertEqualsWithDelta(35.0, $interpolation->interpolate(3.0), 0.1);
    }
}
