<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Stochastic\Distribution\ExponentialDistribution;

/**
 * @internal
 */
final class ExponentialDistributionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ExponentialDistribution
     * @group framework
     */
    public function testPdf() : void
    {
        $lambda = 0.1;
        $x      = 7;

        self::assertEqualsWithDelta(0.049659, ExponentialDistribution::getPdf($x, $lambda), 0.01);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ExponentialDistribution
     * @group framework
     */
    public function testCdf() : void
    {
        $lambda = 0.1;
        $x      = 7;

        self::assertEqualsWithDelta(0.5034, ExponentialDistribution::getCdf($x, $lambda), 0.01);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ExponentialDistribution
     * @group framework
     */
    public function testMean() : void
    {
        self::assertEquals(1 / 3, ExponentialDistribution::getMean(3));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ExponentialDistribution
     * @group framework
     */
    public function testMode() : void
    {
        self::assertEquals(0, ExponentialDistribution::getMode());
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ExponentialDistribution
     * @group framework
     */
    public function testMedian() : void
    {
        self::assertEquals(1 / 3 * \log(2), ExponentialDistribution::getMedian(3));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ExponentialDistribution
     * @group framework
     */
    public function testMgf() : void
    {
        $lambda = 3;
        $t      = 2;

        self::assertEquals($lambda / ($lambda - $t), ExponentialDistribution::getMgf($t, $lambda));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ExponentialDistribution
     * @group framework
     */
    public function testVariance() : void
    {
        self::assertEquals(1 / (3 ** 2), ExponentialDistribution::getVariance(3));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ExponentialDistribution
     * @group framework
     */
    public function testStandardDeviation() : void
    {
        self::assertEquals(\sqrt(1 / (3 ** 2)), ExponentialDistribution::getStandardDeviation(3));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ExponentialDistribution
     * @group framework
     */
    public function testExKurtosis() : void
    {
        self::assertEquals(6, ExponentialDistribution::getExKurtosis());
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ExponentialDistribution
     * @group framework
     */
    public function testSkewness() : void
    {
        self::assertEquals(2, ExponentialDistribution::getSkewness());
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ExponentialDistribution
     * @group framework
     */
    public function testMgfException() : void
    {
        $this->expectException(\OutOfBoundsException::class);

        ExponentialDistribution::getMgf(3, 3);
    }
}
