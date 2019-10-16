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

use phpOMS\Math\Geometry\Shape\D3\Prism;

/**
 * @internal
 */
class PrismTest extends \PHPUnit\Framework\TestCase
{
    public function testVolume() : void
    {
        self::assertEqualsWithDelta(3 * 3 * 12, Prism::getVolumeRegularLength(3, 4, 12), 0.01);
        self::assertEqualsWithDelta(3 * 3 * 12, Prism::getVolumeRegularRadius(1.5, 4, 12), 0.01);
    }

    public function testSurface()
    {
        self::assertEqualsWithDelta(3 * 3 * 2 + 3 * 12 * 4, Prism::getSurfaceRegularLength(3, 4, 12), 0.01);
    }
}
