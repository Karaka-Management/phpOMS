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

use phpOMS\Math\Geometry\Shape\D3\Cylinder;

class CylinderTest extends \PHPUnit\Framework\TestCase
{
    public function testCylinder()
    {
        self::assertEquals(37.7, Cylinder::getVolume(2, 3), '', 0.01);
        self::assertEquals(62.83, Cylinder::getSurface(2, 3), '', 0.01);
        self::assertEquals(37.7, Cylinder::getLateralSurface(2, 3), '', 0.01);
    }
}
