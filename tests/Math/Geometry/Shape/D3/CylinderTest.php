<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Math\Geometry\Shape\D3;

use phpOMS\Math\Geometry\Shape\D3\Cylinder;

/**
 * @internal
 */
class CylinderTest extends \PHPUnit\Framework\TestCase
{
    public function testCylinder() : void
    {
        self::assertEqualsWithDelta(37.7, Cylinder::getVolume(2, 3), 0.01);
        self::assertEqualsWithDelta(62.83, Cylinder::getSurface(2, 3), 0.01);
        self::assertEqualsWithDelta(37.7, Cylinder::getLateralSurface(2, 3), 0.01);
    }
}
