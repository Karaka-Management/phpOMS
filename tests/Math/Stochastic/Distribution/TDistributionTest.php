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

use phpOMS\Math\Stochastic\Distribution\TDistribution;

/**
 * @internal
 */
class TDistributionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Math\Stochastic\Distribution\TDistribution
     * @group framework
     */
    public function testMean() : void
    {
        self::assertEquals(0, TDistribution::getMean());
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\TDistribution
     * @group framework
     */
    public function testMedian() : void
    {
        self::assertEquals(0, TDistribution::getMedian());
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\TDistribution
     * @group framework
     */
    public function testMode() : void
    {
        self::assertEquals(0, TDistribution::getMode());
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\TDistribution
     * @group framework
     */
    public function testVariance() : void
    {
        self::assertEqualsWithDelta(5 / 3, TDistribution::getVariance(5), 0.001);
        self::assertEqualsWithDelta(\PHP_FLOAT_MAX, TDistribution::getVariance(2), 0.001);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\TDistribution
     * @group framework
     */
    public function testStandardDeviation() : void
    {
        self::assertEqualsWithDelta(sqrt(5 / 3), TDistribution::getStandardDeviation(5), 0.001);
        self::assertEqualsWithDelta(\PHP_FLOAT_MAX, TDistribution::getStandardDeviation(2), 0.001);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\TDistribution
     * @group framework
     */
    public function testExKurtosis() : void
    {
        self::assertEqualsWithDelta(6, TDistribution::getExKurtosis(5), 0.001);
        self::assertEqualsWithDelta(\PHP_FLOAT_MAX, TDistribution::getExKurtosis(3), 0.001);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\TDistribution
     * @group framework
     */
    public function testSkewness() : void
    {
        self::assertEquals(0, TDistribution::getSkewness());
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\TDistribution
     * @group framework
     */
    public function testCdf() : void
    {
        self::assertEqualsWithDelta(0.0, TDistribution::getCdf(1.25, 5, 0), 0.001);
        self::assertEqualsWithDelta(0.86669, TDistribution::getCdf(1.25, 5, 1), 0.001);
        self::assertEqualsWithDelta(0.78867, TDistribution::getCdf(1.0, 2, 1), 0.001);
        self::assertEqualsWithDelta(0.4226, TDistribution::getCdf(1.0, 2, 2), 0.001);
    }
}
