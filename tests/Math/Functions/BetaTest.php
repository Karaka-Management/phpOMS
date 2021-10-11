<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Functions;

use phpOMS\Math\Functions\Beta;

/**
 * @testdox phpOMS\tests\Math\Functions\BetaTest: Beta function
 *
 * @internal
 */
class BetaTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The beta function can be approximated
     * @covers phpOMS\Math\Functions\Beta
     * @group framework
     */
    public function beta() : void
    {
        self::assertEqualsWithDelta(1.0, Beta::beta(0, 3), 0.001);
        self::assertEqualsWithDelta(4.4776093, Beta::beta(1.5, 0.2), 0.001);
        self::assertEqualsWithDelta(0.045648, Beta::beta(2, 4), 0.001);
    }

    /**
     * @testdox The log beta function can be approximated
     * @covers phpOMS\Math\Functions\Beta
     * @group framework
     */
    public function testLogBeta() : void
    {
        self::assertEqualsWithDelta(0, Beta::logBeta(1, 0), 0.001);
        self::assertEqualsWithDelta(log(4.4776093), Beta::logBeta(1.5, 0.2), 0.001);
        self::assertEqualsWithDelta(log(Beta::beta(2, 4)), Beta::logBeta(2, 4), 0.001);
    }

    /**
     * @testdox The incomplete beta function can be approximated
     * @covers phpOMS\Math\Functions\Beta
     * @group framework
     */
    public function testIncompleteBeta() : void
    {
        self::assertEqualsWithDelta(0.0, Beta::incompleteBeta(-1, 1, 3), 0.001);
        self::assertEqualsWithDelta(0.3333, Beta::incompleteBeta(1.0, 1, 3), 0.001);
        self::assertEqualsWithDelta(0.0, Beta::incompleteBeta(0.4, 0, 3), 0.001);
        self::assertEqualsWithDelta(0.261333, Beta::incompleteBeta(0.4, 1, 3), 0.001);
        self::assertEqualsWithDelta(0.045648, Beta::incompleteBeta(0.6, 2, 4), 0.001);
    }

    /**
     * @testdox The regularized beta function can be approximated
     * @covers phpOMS\Math\Functions\Beta
     * @group framework
     */
    public function testRegularizedBeta() : void
    {
        self::assertEqualsWithDelta(0.0, Beta::regularizedBeta(-1, 1, 3), 0.001);
        self::assertEqualsWithDelta(1.0, Beta::regularizedBeta(1.0, 1, 3), 0.001);
        self::assertEqualsWithDelta(0.0, Beta::regularizedBeta(0.4, 0, 3), 0.001);
        self::assertEqualsWithDelta(0.784, Beta::regularizedBeta(0.4, 1, 3), 0.001);
        self::assertEqualsWithDelta(0.04, Beta::regularizedBeta(0.2, 2, 1), 0.001);
        self::assertEqualsWithDelta(0.91296, Beta::regularizedBeta(0.6, 2, 4), 0.001);
    }
}
