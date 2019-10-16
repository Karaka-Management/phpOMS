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

namespace phpOMS\tests\Math\Numerics\Interpolation;

use phpOMS\Math\Numerics\Interpolation\CubicSplineInterpolation;
use phpOMS\Math\Numerics\Interpolation\DerivativeType;

/**
 * @internal
 */
class CubicSplineInterpolationTest extends \PHPUnit\Framework\TestCase
{
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
}
