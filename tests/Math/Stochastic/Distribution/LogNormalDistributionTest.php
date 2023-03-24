<?php
/**
 * Karaka
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

use phpOMS\Math\Stochastic\Distribution\LogNormalDistribution;

/**
 * @internal
 */
final class LogNormalDistributionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogNormalDistribution
     * @group framework
     */
    public function testPdf() : void
    {
        self::assertEqualsWithDelta(0.060069054, LogNormalDistribution::getPdf(3, 2, 2), 0.001);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogNormalDistribution
     * @group framework
     */
    public function testCdf() : void
    {
        self::assertEqualsWithDelta(0.32610510, LogNormalDistribution::getCdf(3, 2, 2), 0.001);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogNormalDistribution
     * @group framework
     */
    public function testMean() : void
    {
        self::assertEqualsWithDelta(\exp(13 / 2), LogNormalDistribution::getMean(2, 3), 0.001);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogNormalDistribution
     * @group framework
     */
    public function testVariance() : void
    {
        self::assertEqualsWithDelta(
            (\exp(9) - 1) * \exp(13),
            LogNormalDistribution::getVariance(2, 3), 0.001
        );
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogNormalDistribution
     * @group framework
     */
    public function testStandardDeviation() : void
    {
        self::assertEqualsWithDelta(
            \exp(13 / 2) * \sqrt(\exp(9) - 1),
            LogNormalDistribution::getStandardDeviation(2, 3), 0.001
        );

        self::assertEqualsWithDelta(
            \sqrt((\exp(9) - 1) * \exp(13)),
            LogNormalDistribution::getStandardDeviation(2, 3), 0.001
        );
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogNormalDistribution
     * @group framework
     */
    public function testSkewness() : void
    {
        self::assertEqualsWithDelta(
            \sqrt(\exp(9) - 1) * (\exp(9) + 2),
            LogNormalDistribution::getSkewness(3), 0.001
        );
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogNormalDistribution
     * @group framework
     */
    public function testExKurtosis() : void
    {
        self::assertEqualsWithDelta(
            \exp(16) + 2 * \exp(12) + 3 * \exp(8) - 6,
            LogNormalDistribution::getExKurtosis(2), 0.001
        );
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogNormalDistribution
     * @group framework
     */
    public function testMedian() : void
    {
        self::assertEquals(\exp(3), LogNormalDistribution::getMedian(3));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogNormalDistribution
     * @group framework
     */
    public function testMode() : void
    {
        self::assertEquals(\exp(3 - 4 ** 2), LogNormalDistribution::getMode(3, 4));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogNormalDistribution
     * @group framework
     */
    public function testEntropy() : void
    {
        self::assertEqualsWithDelta(
            \log(4 * \exp(3 + 0.5) * \sqrt(2 * \M_PI), 2),
            LogNormalDistribution::getEntropy(3, 4), 0.001
        );
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LogNormalDistribution
     * @group framework
     */
    public function testFisherInformation() : void
    {
        self::assertEquals(
            [
                [1 / 3 ** 2, 0],
                [0, 1 / (2 * 3 ** 4)],
            ],
            LogNormalDistribution::getFisherInformation(3)
        );
    }
}
