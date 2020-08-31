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

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Stochastic\Distribution\ZTest;

/**
 * @internal
 */
class ZTestTest extends \PHPUnit\Framework\TestCase
{
    // http://sphweb.bumc.bu.edu/otlt/MPH-Modules/BS/BS704_HypothesisTesting-ChiSquare/BS704_HypothesisTesting-ChiSquare_print.html
    public function testHypothesisFalse() : void
    {
        $a        = 0.95;
        $observed = 0.512;
        $expected = 0.75;
        $total    = 125; // total count of observed sample size

        self::assertFalse(ZTest::testHypothesis($observed, $expected, $total, $a));
    }

    // https://support.microsoft.com/en-us/office/z-test-function-d633d5a3-2031-4614-a016-92180ad82bee?ui=en-us&rs=en-us&ad=us
    public function testZTest() : void
    {
        //self::assertEqualsWithDelta(0.090574, ZTest::zTest(4, [3, 6, 7, 8, 6, 5, 4, 2, 1, 9]), 0.001);
        self::markTestIncomplete();
        self::assertTrue(true);
    }
}
