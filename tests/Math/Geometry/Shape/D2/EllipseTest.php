<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Math\Shape\D2;


use phpOMS\Math\Geometry\Shape\D2\Ellipse;

class EllipseTest extends \PHPUnit\Framework\TestCase
{
    public function testEllipse()
    {
        self::assertEquals(6.28, Ellipse::getSurface(2, 1), '', 0.01);
        self::assertEquals(9.69, Ellipse::getPerimeter(2, 1), '', 0.01);
    }
}
