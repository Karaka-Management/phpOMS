<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Stochastic\Distribution\FDistribution;

/**
 * @internal
 */
final class FDistributionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Math\Stochastic\Distribution\FDistribution
     * @group framework
     */
    public function testMean() : void
    {
        self::assertEquals(0.0, FDistribution::getMean(2));
        self::assertEquals(2, FDistribution::getMean(4));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\FDistribution
     * @group framework
     */
    public function testMode() : void
    {
        self::assertEquals(0.0, FDistribution::getMode(0, 0));
        self::assertEqualsWithDelta(0.0, FDistribution::getMode(2, 3), 0.01);
        self::assertEqualsWithDelta(1 / 3 * 2 / 3, FDistribution::getMode(3, 4), 0.01);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\FDistribution
     * @group framework
     */
    public function testVariance() : void
    {
        self::assertEquals(0.0, FDistribution::getVariance(1, 2));
        self::assertEquals(0.0, FDistribution::getVariance(1, 4));
        self::assertEqualsWithDelta(11.1111, FDistribution::getVariance(3, 5), 0.01);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\FDistribution
     * @group framework
     */
    public function testStandardDeviation() : void
    {
        self::assertEquals(0.0, FDistribution::getStandardDeviation(1, 2));
        self::assertEquals(0.0, FDistribution::getStandardDeviation(1, 4));
        self::assertEqualsWithDelta(\sqrt(11.1111), FDistribution::getStandardDeviation(3, 5), 0.01);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\FDistribution
     * @group framework
     */
    public function testSkewness() : void
    {
        self::assertEquals(0.0, FDistribution::getSkewness(1, 6));
        self::assertEquals(2 * (2 * 4 + 7 - 2) / (7 - 6) * \sqrt(2 * (7 - 4) / (4 * (7 + 4 - 2))), FDistribution::getSkewness(4, 7));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\FDistribution
     * @group framework
     */
    public function testPdf() : void
    {
        self::assertEqualsWithDelta(0.2788548, FDistribution::getPdf(1, 2, 3), 0.001);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\FDistribution
     * @group framework
     */
    public function testCdf() : void
    {
        self::assertEqualsWithDelta(0.5352419, FDistribution::getCdf(1, 2, 3), 0.001);
    }
}
