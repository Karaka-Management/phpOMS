<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Statistic\Average;
use phpOMS\Math\Statistic\MeasureOfDispersion;
use phpOMS\Math\Stochastic\Distribution\ZTesting;

/**
 * @internal
 */
final class ZTestingTest extends \PHPUnit\Framework\TestCase
{
    // http://sphweb.bumc.bu.edu/otlt/MPH-Modules/BS/BS704_HypothesisTesting-ChiSquare/BS704_HypothesisTesting-ChiSquare_print.html

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ZTesting
     * @group framework
     */
    public function testHypothesisFalse() : void
    {
        $a        = 0.95;
        $observed = 0.512;
        $expected = 0.75;
        $total    = 125; // total count of observed sample size

        self::assertFalse(ZTesting::testHypothesis($observed, $expected, $total, $a));
    }

    // https://support.microsoft.com/en-us/office/z-test-function-d633d5a3-2031-4614-a016-92180ad82bee?ui=en-us&rs=en-us&ad=us

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ZTesting
     * @group framework
     */
    public function testZTest() : void
    {
        self::assertEqualsWithDelta(0.090574, ZTesting::zTest(4, [3, 6, 7, 8, 6, 5, 4, 2, 1, 9]), 0.001);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ZTesting
     * @group framework
     */
    public function testZTestValues() : void
    {
        $data = [3, 6, 7, 8, 6, 5, 4, 2, 1, 9];
        $mean = Average::arithmeticMean($data);
        $size = \count($data);
        $sig  = MeasureOfDispersion::standardDeviationSample($data);

        self::assertEqualsWithDelta(0.090574, ZTesting::zTestValues(4, $mean, $size, $sig), 0.001);
    }
}
