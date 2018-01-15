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

use phpOMS\Math\Geometry\Shape\D3\Cuboid;

class CuboidTest extends \PHPUnit\Framework\TestCase
{
    public function testCuboid()
    {
        self::assertEquals(200, Cuboid::getVolume(10, 5, 4), '', 0.001);
        self::assertEquals(220, Cuboid::getSurface(10, 5, 4), '', 0.001);
    }
}
