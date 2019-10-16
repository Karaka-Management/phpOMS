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

namespace phpOMS\tests\Math\Geometry\Shape\D3;

use phpOMS\Math\Geometry\Shape\D3\RectangularPyramid;

/**
 * @internal
 */
class RectangularPyramidTest extends \PHPUnit\Framework\TestCase
{
    public function testCylinder() : void
    {
        self::assertEqualsWithDelta(8, RectangularPyramid::getVolume(2, 3, 4), 0.01);
        self::assertEqualsWithDelta(26.91, RectangularPyramid::getSurface(2, 3, 4), 0.01);
        self::assertEqualsWithDelta(20.91, RectangularPyramid::getLateralSurface(2, 3, 4), 0.01);
    }
}
