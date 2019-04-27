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
 declare(strict_types=1);

namespace phpOMS\tests\Math\Geometry\Shape\D3;

use phpOMS\Math\Geometry\Shape\D3\Cuboid;

/**
 * @internal
 */
class CuboidTest extends \PHPUnit\Framework\TestCase
{
    public function testCuboid() : void
    {
        self::assertEqualsWithDelta(200, Cuboid::getVolume(10, 5, 4), 0.001);
        self::assertEqualsWithDelta(220, Cuboid::getSurface(10, 5, 4), 0.001);
    }
}
