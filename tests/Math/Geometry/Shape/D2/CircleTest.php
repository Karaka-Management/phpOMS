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

namespace phpOMS\tests\Math\Geometry\Shape\D2;

use phpOMS\Math\Geometry\Shape\D2\Circle;

class CircleTest extends \PHPUnit\Framework\TestCase
{
    public function testCircle()
    {
        self::assertEquals(12.57, Circle::getSurface(2), '', 0.01);
        self::assertEquals(12.57, Circle::getPerimeter(2), '', 0.01);
        self::assertEquals(2.0, Circle::getRadiusBySurface(Circle::getSurface(2)), '', 0.001);
        self::assertEquals(2.0, Circle::getRadiusByPerimeter(Circle::getPerimeter(2)), '', 0.001);
    }
}
