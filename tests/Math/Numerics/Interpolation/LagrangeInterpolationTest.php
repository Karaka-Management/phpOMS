<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Numerics\Interpolation;

use phpOMS\Math\Numerics\Interpolation\LagrangeInterpolation;

/**
 * @testdox phpOMS\tests\Math\Numerics\Interpolation\LagrangeInterpolationTest: Lagrange interpolation
 *
 * @internal
 */
final class LagrangeInterpolationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The lagrange interpolation is correct
     * @covers phpOMS\Math\Numerics\Interpolation\LagrangeInterpolation
     * @group framework
     */
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
