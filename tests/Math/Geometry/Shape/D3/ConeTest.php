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

use phpOMS\Math\Geometry\Shape\D3\Cone;

/**
 * @internal
 */
class ConeTest extends \PHPUnit\Framework\TestCase
{
    public function testCone() : void
    {
        self::assertEqualsWithDelta(12.57, Cone::getVolume(2, 3), 0.01);
        self::assertEqualsWithDelta(35.22, Cone::getSurface(2, 3), 0.01);
        self::assertEqualsWithDelta(3.61, Cone::getSlantHeight(2, 3), 0.01);
        self::assertEqualsWithDelta(3, Cone::getHeightFromVolume(12.57, 2), 0.01);
    }
}
