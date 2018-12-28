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

use phpOMS\Math\Geometry\Shape\D2\Triangle;

class TriangleTest extends \PHPUnit\Framework\TestCase
{
    public function testTriangle() : void
    {
        self::assertEquals(3, Triangle::getSurface(2, 3), '', 0.001);
        self::assertEquals(9, Triangle::getPerimeter(2, 3, 4), '', 0.001);
        self::assertEquals(3, Triangle::getHeight(3, 2), '', 0.001);
        self::assertEquals(5, Triangle::getHypot(4, 3), '', 0.001);
    }
}
