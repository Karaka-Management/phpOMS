<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Math\Geometry\Shape\D3;

use phpOMS\Math\Geometry\Shape\D3\Tetrahedron;

class TetrahedronTest extends \PHPUnit\Framework\TestCase
{
    public function testTetrahedron()
    {
        self::assertEquals(3.18, Tetrahedron::getVolume(3), '', 0.01);
        self::assertEquals(15.59, Tetrahedron::getSurface(3), '', 0.01);
        self::assertEquals(3.9, Tetrahedron::getFaceArea(3), '', 0.01);
    }
}
