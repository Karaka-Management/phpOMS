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
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Stochastic\Distribution\BetaDistribution;

/**
 * @internal
 */
final class BetaDistributionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Math\Stochastic\Distribution\BetaDistribution
     * @group framework
     */
    public function testMean() : void
    {
        self::assertEquals(1 / 2, BetaDistribution::getMean(2.0, 2.0));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\BetaDistribution
     * @group framework
     */
    public function testMode() : void
    {
        self::assertEquals(1 / 2, BetaDistribution::getMode(2.0, 2.0));
        self::assertEqualsWithDelta(0.2, BetaDistribution::getMode(2.0, 5.0), 0.1);
        self::assertEquals(0.0, BetaDistribution::getMode(1.0, 2.0));
        self::assertEquals(1.0, BetaDistribution::getMode(1.0, 1.0));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\BetaDistribution
     * @group framework
     */
    public function testVariance() : void
    {
        self::assertEqualsWithDelta(1 / 20, BetaDistribution::getVariance(2.0, 2.0), 0.001);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\BetaDistribution
     * @group framework
     */
    public function testStandardDeviation() : void
    {
        self::assertEqualsWithDelta(\sqrt(1 / 20), BetaDistribution::getStandardDeviation(2.0, 2.0), 0.001);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\BetaDistribution
     * @group framework
     */
    public function testSkewness() : void
    {
        self::assertEqualsWithDelta(0, BetaDistribution::getSkewness(2.0, 2.0), 0.001);
        self::assertEqualsWithDelta(0.565685, BetaDistribution::getSkewness(1.0, 2.0), 0.001);
        self::assertEqualsWithDelta(-0.565685, BetaDistribution::getSkewness(2.0, 1.0), 0.001);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\BetaDistribution
     * @group framework
     */
    public function testExKurtosis() : void
    {
        self::assertEqualsWithDelta(-6 / 7, BetaDistribution::getExKurtosis(2.0, 2.0), 0.001);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\BetaDistribution
     * @group framework
     */
    public function testPdf() : void
    {
        self::assertEqualsWithDelta(0.9375, BetaDistribution::getPdf(0.5, 2, 5), 0.001);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\BetaDistribution
     * @group framework
     */
    public function testCdf() : void
    {
        self::assertEqualsWithDelta(0.890625, BetaDistribution::getCdf(0.5, 2, 5), 0.001);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\BetaDistribution
     * @group framework
     */
    public function testMgf() : void
    {
        self::assertEqualsWithDelta(1.0, BetaDistribution::getMgf(0, 2, 5), 0.001);
        self::assertEqualsWithDelta(1.869356, BetaDistribution::getMgf(2, 2, 5), 0.001);
    }
}
