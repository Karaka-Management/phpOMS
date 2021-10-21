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

use phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution;

/**
 * @internal
 */
final class ChiSquaredDistributionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution
     * @group framework
     */
    public function testHypothesisFalse() : void
    {
        $p = [0.6, 0.25, 0.15];
        $a = 0.05;

        $total    = 470;
        $observed = [255, 125, 90];
        $expected = [$total * $p[0], $total * $p[1], $total * $p[2]];

        $test = ChiSquaredDistribution::testHypothesis($observed, $expected, $a);

        self::assertEqualsWithDelta(8.46, $test['Chi2'], 0.1);
        self::assertNotEquals(0, $test['P']);
        self::assertFalse($test['H0']);
        self::assertEquals(2, $test['df']);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution
     * @group framework
     */
    public function testDegreesOfFreedom() : void
    {
        self::assertEquals(2, ChiSquaredDistribution::getDegreesOfFreedom([1, 2, 3]));
        self::assertEquals(6, ChiSquaredDistribution::getDegreesOfFreedom([
            [1, 2, 3, 4],
            [1, 2, 3, 4],
            [1, 2, 3, 4],
        ]));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution
     * @group framework
     */
    public function testMode() : void
    {
        self::assertEquals(\max(5 - 2, 0), ChiSquaredDistribution::getMode(5));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution
     * @group framework
     */
    public function testMean() : void
    {
        $df = 5;

        self::assertEquals($df, ChiSquaredDistribution::getMean($df));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution
     * @group framework
     */
    public function testVariance() : void
    {
        $df = 5;

        self::assertEquals(2 * $df, ChiSquaredDistribution::getVariance($df));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution
     * @group framework
     */
    public function testStandardDeviation() : void
    {
        $df = 5;

        self::assertEquals(\sqrt(2 * $df), ChiSquaredDistribution::getStandardDeviation($df));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution
     * @group framework
     */
    public function testMedian() : void
    {
        $df = 5;

        self::assertEquals($df * (1 - 2 / (9 * $df)) ** 3, ChiSquaredDistribution::getMedian($df));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution
     * @group framework
     */
    public function testSkewness() : void
    {
        $df = 5;

        self::assertEquals(\sqrt(8 / $df), ChiSquaredDistribution::getSkewness($df));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution
     * @group framework
     */
    public function testExKurtosis() : void
    {
        $df = 5;

        self::assertEquals(12 / $df, ChiSquaredDistribution::getExKurtosis($df));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution
     * @group framework
     */
    public function testMgdf() : void
    {
        $df = 5;
        $t  = 0.3;

        self::assertEquals((1 - 2 * $t) ** (-$df / 2), ChiSquaredDistribution::getMgf($df, $t));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution
     * @group framework
     */
    public function testPdf() : void
    {
        self::assertEqualsWithDelta(0.20755, ChiSquaredDistribution::getPdf(2, 3), 0.001);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution
     * @group framework
     */
    public function testCdf() : void
    {
        self::assertEqualsWithDelta(0.42759, ChiSquaredDistribution::getCdf(2, 3), 0.001);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution
     * @group framework
     */
    public function testHypothesisSizeException() : void
    {
        $this->expectException(\Exception::class);

        ChiSquaredDistribution::testHypothesis([1, 2], [2]);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution
     * @group framework
     */
    public function testHypothesisDegreesOfFreedomException() : void
    {
        $this->expectException(\Exception::class);

        ChiSquaredDistribution::testHypothesis([], []);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution
     * @group framework
     */
    public function testPdfOutOfBoundsException() : void
    {
        $this->expectException(\OutOfBoundsException::class);

        ChiSquaredDistribution::getPdf(-1, 0);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution
     * @group framework
     */
    public function testMgfOutOfBoundsException() : void
    {
        $this->expectException(\OutOfBoundsException::class);

        ChiSquaredDistribution::getMgf(1, 0.6);
    }
}
