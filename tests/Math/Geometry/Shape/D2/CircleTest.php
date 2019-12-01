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

namespace phpOMS\tests\Math\Geometry\Shape\D2;

use phpOMS\Math\Geometry\Shape\D2\Circle;

/**
 * @internal
 */
class CircleTest extends \PHPUnit\Framework\TestCase
{
    public function testSurface() : void
    {
        self::assertEqualsWithDelta(12.57, Circle::getSurface(2), 0.01);
    }

    public function testPerimeter() : void
    {
        self::assertEqualsWithDelta(12.57, Circle::getPerimeter(2), 0.01);
    }

    public function testRadiusBySurface() : void
    {
        self::assertEqualsWithDelta(2.0, Circle::getRadiusBySurface(Circle::getSurface(2)), 0.001);
    }

    public function testRadiusByPerimeter() : void
    {
        self::assertEqualsWithDelta(2.0, Circle::getRadiusByPerimeter(Circle::getPerimeter(2)), 0.001);
    }
}
