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
        $a = 0.95;
        $observed = 0.512;
        $expected = 0.75;
        $total    = 125; // total count of observed sample size

        self::assertFalse(ZTest::testHypothesis($observed, $expected, $total, $a));
    }
}
