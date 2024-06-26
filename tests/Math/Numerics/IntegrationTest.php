<?php

/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Numerics;

use phpOMS\Math\Numerics\Integration;

/**
 * @internal
 *
 * Commented out assertions which take a long time with xdebug. without xdebug these are fine!
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Numerics\Integration::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Numerics\IntegrationTest: Numeric integration')]
final class IntegrationTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Integration by summing up rectangle areas from the left side')]
    public function testLRect() : void
    {
        self::assertEqualsWithDelta(0.235322, Integration::intLeftRect(0.0, 1.0, 100.0, function($x) { return $x ** 3; }), 0.001);
        self::assertEqualsWithDelta(4.654000, Integration::intLeftRect(1.0, 100.0, 1000.0, function($x) { return 1 / $x; }), 0.001);
        //self::assertEqualsWithDelta(12499992.500730, Integration::intLeftRect(0.0, 5000.0, 5000000.0, function($x) { return $x; }), 0.001);
        //self::assertEqualsWithDelta(17999991.001392, Integration::intLeftRect(0.0, 6000.0, 6000000.0, function($x) { return $x; }), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Integration by summing up rectangle areas from the right side')]
    public function testRRect() : void
    {
        self::assertEqualsWithDelta(0.245025, Integration::intRightRect(0.0, 1.0, 100.0, function($x) { return $x ** 3; }), 0.001);
        self::assertEqualsWithDelta(4.555991, Integration::intRightRect(1.0, 100.0, 1000.0, function($x) { return 1 / $x; }), 0.001);
        //self::assertEqualsWithDelta(12499997.500729, Integration::intRightRect(0.0, 5000.0, 5000000.0, function($x) { return $x; }), 0.001);
        //self::assertEqualsWithDelta(17999997.001390, Integration::intRightRect(0.0, 6000.0, 6000000.0, function($x) { return $x; }), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Integration by summing up rectangle areas from the middle')]
    public function testMRect() : void
    {
        self::assertEqualsWithDelta(0.240137, Integration::intMiddleRect(0.0, 1.0, 100.0, function($x) { return $x ** 3; }), 0.001);
        self::assertEqualsWithDelta(4.603772, Integration::intMiddleRect(1.0, 100.0, 1000.0, function($x) { return 1 / $x; }), 0.001);
        //self::assertEqualsWithDelta(12499995.000729, Integration::intMiddleRect(0.0, 5000.0, 5000000.0, function($x) { return $x; }), 0.001);
        //self::assertEqualsWithDelta(17999994.001391, Integration::intMiddleRect(0.0, 6000.0, 6000000.0, function($x) { return $x; }), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Integration by summing up trapezoid areas')]
    public function testTrapeze() : void
    {
        self::assertEqualsWithDelta(0.250025, Integration::intTrapezium(0.0, 1.0, 100.0, function($x) { return $x ** 3; }), 0.001);
        self::assertEqualsWithDelta(4.605986, Integration::intTrapezium(1.0, 100.0, 1000.0, function($x) { return 1 / $x; }), 0.001);
        //self::assertEqualsWithDelta(12500000.0, Integration::intTrapezium(0.0, 5000.0, 5000000.0, function($x) { return $x; }), 0.001);
        //self::assertEqualsWithDelta(18000000.0, Integration::intTrapezium(0.0, 6000.0, 6000000.0, function($x) { return $x; }), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Integration by using the simpson formula')]
    public function testSimpson() : void
    {
        self::assertEqualsWithDelta(0.25, Integration::intSimpson(0.0, 1.0, 100.0, function ($x) { return $x ** 3; }), 0.001);
        self::assertEqualsWithDelta(4.605170, Integration::intSimpson(1.0, 100.0, 1000.0, function ($x) { return 1 / $x; }), 0.001);
        //self::assertEqualsWithDelta(12500000.0, Integration::intSimpson(0.0, 5000.0, 5000000.0, function ($x) { return $x; }), 0.001);
        //self::assertEqualsWithDelta(18000000.0, Integration::intSimpson(0.0, 6000.0, 6000000.0, function ($x) { return $x; }), 0.001);
    }
}
