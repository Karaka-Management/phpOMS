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

namespace phpOMS\tests\Math\Geometry\Shape\D2;

use phpOMS\Math\Geometry\Shape\D2\Quadrilateral;

/**
 * @testdox phpOMS\tests\Math\Geometry\Shape\D2\QuadrilateralTest: Quadrilateral shape
 *
 * @internal
 */
final class QuadrilateralTest extends \PHPUnit\Framework\TestCase
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
