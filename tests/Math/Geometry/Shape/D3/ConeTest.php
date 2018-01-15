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

namespace phpOMS\tests\Math\Geometry\Shape\D3;


use phpOMS\Math\Geometry\Shape\D3\Cone;

class ConeTest extends \PHPUnit\Framework\TestCase
{
    public function testCone()
    {
        self::assertEquals(12.57, Cone::getVolume(2, 3), '', 0.01);
        self::assertEquals(35.22, Cone::getSurface(2, 3), '', 0.01);
        self::assertEquals(3.61, Cone::getSlantHeight(2, 3), '', 0.01);
        self::assertEquals(3, Cone::getHeightFromVolume(12.57, 2), '', 0.01);
    }
}
