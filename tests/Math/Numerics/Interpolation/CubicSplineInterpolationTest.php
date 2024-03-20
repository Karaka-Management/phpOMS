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

namespace phpOMS\tests\Math\Numerics\Interpolation;

use phpOMS\Math\Numerics\Interpolation\CubicSplineInterpolation;
use phpOMS\Math\Numerics\Interpolation\DerivativeType;

/**
 * @testdox phpOMS\tests\Math\Numerics\Interpolation\CubicSplineInterpolationTest: Cubic spline interpolation
 *
 * @internal
 */
final class CubicSplineInterpolationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The spline interpolation using the first derivative is correct
     * @covers \phpOMS\Math\Numerics\Interpolation\CubicSplineInterpolation
     * @group framework
     */
    public function testInterpolationFirstDerivative() : void
    {
        $interpolation = new CubicSplineInterpolation([
            ['x' => 0.1, 'y' => 0.1],
            ['x' => 0.4, 'y' => 0.7],
            ['x' => 1.2, 'y' => 0.6],
            ['x' => 1.8, 'y' => 1.1],
            ['x' => 2.0, 'y' => 0.9],
        ],
        0.0, DerivativeType::FIRST,
        0.0, DerivativeType::FIRST,
    );

        self::assertEqualsWithDelta(0.947888, $interpolation->interpolate(1.5), 0.1);
    }

    /**
     * @testdox The spline interpolation using the second derivative is correct
     * @covers \phpOMS\Math\Numerics\Interpolation\CubicSplineInterpolation
     * @group framework
     */
    public function testInterpolationSecondDerivative() : void
    {
        $interpolation = new CubicSplineInterpolation([
            ['x' => 0.1, 'y' => 0.1],
            ['x' => 0.4, 'y' => 0.7],
            ['x' => 1.2, 'y' => 0.6],
            ['x' => 1.8, 'y' => 1.1],
            ['x' => 2.0, 'y' => 0.9],
        ],
        0.0, DerivativeType::SECOND,
        0.0, DerivativeType::SECOND,
    );

        self::assertEqualsWithDelta(0.915345, $interpolation->interpolate(1.5), 0.1);
    }

    /**
     * @testdox The spline interpolation for out of bounds values uses linear extrapolation
     * @covers \phpOMS\Math\Numerics\Interpolation\CubicSplineInterpolation
     * @group framework
     */
    public function testInterpolationUnderOverflow() : void
    {
        $interpolation = new CubicSplineInterpolation([
            ['x' => 0.1, 'y' => 0.1],
            ['x' => 0.4, 'y' => 0.7],
            ['x' => 1.2, 'y' => 0.6],
            ['x' => 1.8, 'y' => 1.1],
            ['x' => 2.0, 'y' => 0.9],
        ],
        0.0, DerivativeType::SECOND,
        0.0, DerivativeType::SECOND,
    );

        self::assertEqualsWithDelta(-0.140528, $interpolation->interpolate(0), 0.001);
        self::assertEqualsWithDelta(-0.016007, $interpolation->interpolate(2.5), 0.001);
    }
}
