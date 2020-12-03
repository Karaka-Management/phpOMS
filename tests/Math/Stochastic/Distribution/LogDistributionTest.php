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

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Stochastic\Distribution\LogDistribution;

/**
 * @internal
 */
class LogDistributionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogDistribution
     * @group framework
     */
    public function testPmf() : void
    {
        $p = 0.3;
        $k = 4;

        self::assertEquals(
            -1 / \log(1 - $p) * $p ** $k / $k,
            LogDistribution::getPmf($p, $k)
        );
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogDistribution
     * @group framework
     */
    public function testCdf() : void
    {
        $p = 6 / 9;
        $k = 2;

        self::assertEqualsWithDelta(
            0.8091,
            LogDistribution::getCdf($p, $k), 0.001
        );
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogDistribution
     * @group framework
     */
    public function testMean() : void
    {
        $p = 0.3;

        self::assertEquals(-1 / \log(1 - $p) * $p / (1 - $p), LogDistribution::getMean($p));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogDistribution
     * @group framework
     */
    public function testMode() : void
    {
        self::assertEquals(1, LogDistribution::getMode());
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogDistribution
     * @group framework
     */
    public function testVariance() : void
    {
        $p = 0.3;

        self::assertEquals(
            -($p ** 2 + $p * \log(1 - $p)) / ((1 - $p) ** 2 * (\log(1 - $p)) ** 2),
            LogDistribution::getVariance($p)
        );
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogDistribution
     * @group framework
     */
    public function testStandardDeviation() : void
    {
        $p = 0.3;

        self::assertEquals(
            \sqrt(-($p ** 2 + $p * \log(1 - $p)) / ((1 - $p) ** 2 * (\log(1 - $p)) ** 2)),
            LogDistribution::getStandardDeviation($p)
        );
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogDistribution
     * @group framework
     */
    public function testMgf() : void
    {
        $p = 0.3;
        $t = 0.8;

        self::assertEquals(
            \log(1 - $p * \exp($t)) / \log(1 - $p),
            LogDistribution::getMgf($p, $t)
        );
    }
}
