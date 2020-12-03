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

namespace phpOMS\tests\Math\Geometry\Shape\D2;

use phpOMS\Math\Geometry\Shape\D2\Quadrilateral;

/**
 * @testdox phpOMS\tests\Math\Geometry\Shape\D2\QuadrilateralTest: Quadrilateral shape
 *
 * @internal
 */
class QuadrilateralTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The area can be calculated
     * @covers phpOMS\Math\Geometry\Shape\D2\Quadrilateral
     * @group framework
     */
    public function testArea() : void
    {
        self::assertEqualsWithDelta(10.78, Quadrilateral::getSurfaceFromSidesAndAngle(4.0, 2.0, 4.0, 3.5, 106.56), 0.01);
    }
}
