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

use phpOMS\Math\Numerics\Interpolation\LinearInterpolation;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Numerics\Interpolation\LinearInterpolation::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Numerics\Interpolation\LinearInterpolationTest: Linear interpolation')]
final class LinearInterpolationTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The linear interpolation is correct')]
    public function testInterpolation() : void
    {
        $interpolation = new LinearInterpolation([
            ['x' => 10.0, 'y' => 0.5],
            ['x' => 20.0, 'y' => 1.0],
            ['x' => 30.0, 'y' => 3.0],
            ['x' => 40.0, 'y' => 5.0],
            ['x' => 50.0, 'y' => 7.0],
        ]);

        self::assertEqualsWithDelta(0.45, $interpolation->interpolate(9.0), 0.1);
        self::assertEqualsWithDelta(4.4, $interpolation->interpolate(37.0), 0.1);
        self::assertEqualsWithDelta(10, $interpolation->interpolate(55.0), 0.1);
    }
}
