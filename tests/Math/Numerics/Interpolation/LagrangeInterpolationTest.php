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

use phpOMS\Math\Numerics\Interpolation\LagrangeInterpolation;

/**
 * @internal
 */
class LagrangeInterpolationTest extends \PHPUnit\Framework\TestCase
{
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