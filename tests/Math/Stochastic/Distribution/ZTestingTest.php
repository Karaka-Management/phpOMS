<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Stochastic\Distribution;

include_once __DIR__ . '/../../../Autoloader.php';

use phpOMS\Math\Statistic\Average;
use phpOMS\Math\Statistic\MeasureOfDispersion;
use phpOMS\Math\Stochastic\Distribution\ZTesting;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Stochastic\Distribution\ZTesting::class)]
final class ZTestingTest extends \PHPUnit\Framework\TestCase
{
    // http://sphweb.bumc.bu.edu/otlt/MPH-Modules/BS/BS704_HypothesisTesting-ChiSquare/BS704_HypothesisTesting-ChiSquare_print.html
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testHypothesisFalse() : void
    {
        $a        = 0.95;
        $observed = 0.512;
        $expected = 0.75;
        $total    = 125; // total count of observed sample size

        self::assertFalse(ZTesting::testHypothesis($observed, $expected, $total, $a));
    }

    // https://support.microsoft.com/en-us/office/z-test-function-d633d5a3-2031-4614-a016-92180ad82bee?ui=en-us&rs=en-us&ad=us
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testZTest() : void
    {
        self::assertEqualsWithDelta(0.090574, ZTesting::zTest(4, [3, 6, 7, 8, 6, 5, 4, 2, 1, 9]), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testZTestValues() : void
    {
        $data = [3, 6, 7, 8, 6, 5, 4, 2, 1, 9];
        $mean = Average::arithmeticMean($data);
        $size = \count($data);
        $sig  = MeasureOfDispersion::standardDeviationSample($data);

        self::assertEqualsWithDelta(0.090574, ZTesting::zTestValues(4, $mean, $size, $sig), 0.001);
    }
}
