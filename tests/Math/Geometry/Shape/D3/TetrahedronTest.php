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
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Math\Geometry\Shape\D3;

use phpOMS\Math\Geometry\Shape\D3\Tetrahedron;

/**
 * @internal
 */
class TetrahedronTest extends \PHPUnit\Framework\TestCase
{
    public function testTetrahedron() : void
    {
        self::assertEqualsWithDelta(3.18, Tetrahedron::getVolume(3), 0.01);
        self::assertEqualsWithDelta(15.59, Tetrahedron::getSurface(3), 0.01);
        self::assertEqualsWithDelta(3.9, Tetrahedron::getFaceArea(3), 0.01);
    }
}
