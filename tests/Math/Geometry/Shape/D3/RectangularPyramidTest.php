<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Math\Geometry\Shape\D3;

use phpOMS\Math\Geometry\Shape\D3\RectangularPyramid;

class RectangularPyramidTest extends \PHPUnit\Framework\TestCase
{
    public function testCylinder() : void
    {
        self::assertEquals(8, RectangularPyramid::getVolume(2, 3, 4), '', 0.01);
        self::assertEquals(26.91, RectangularPyramid::getSurface(2, 3, 4), '', 0.01);
        self::assertEquals(20.91, RectangularPyramid::getLateralSurface(2, 3, 4), '', 0.01);
    }
}
