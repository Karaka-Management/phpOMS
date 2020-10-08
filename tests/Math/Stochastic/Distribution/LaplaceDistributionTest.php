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

use phpOMS\Math\Stochastic\Distribution\LaplaceDistribution;

/**
 * @internal
 */
class LaplaceDistributionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LaplaceDistribution
     * @group framework
     */
    public function testPdf() : void
    {
        $x = 2;
        $m = 1;
        $b = 0.7;

        self::assertEqualsWithDelta(0.17118, LaplaceDistribution::getPdf($x, $m, $b), 0.01);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LaplaceDistribution
     * @group framework
     */
    public function testCdf() : void
    {
        $x = 2;
        $m = 1;
        $b = 0.7;

        self::assertEqualsWithDelta(0.88017, LaplaceDistribution::getCdf($x, $m, $b), 0.01);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LaplaceDistribution
     * @group framework
     */
    public function testMode() : void
    {
        self::assertEquals(2, LaplaceDistribution::getMode(2));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LaplaceDistribution
     * @group framework
     */
    public function testMean() : void
    {
        self::assertEquals(2, LaplaceDistribution::getMean(2));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LaplaceDistribution
     * @group framework
     */
    public function testMedian() : void
    {
        self::assertEquals(2, LaplaceDistribution::getMedian(2));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LaplaceDistribution
     * @group framework
     */
    public function testExKurtosis() : void
    {
        self::assertEquals(3, LaplaceDistribution::getExKurtosis());
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LaplaceDistribution
     * @group framework
     */
    public function testSkewness() : void
    {
        self::assertEquals(0, LaplaceDistribution::getSkewness());
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LaplaceDistribution
     * @group framework
     */
    public function testVariance() : void
    {
        $b = 3;

        self::assertEquals(2 * $b ** 2, LaplaceDistribution::getVariance($b));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LaplaceDistribution
     * @group framework
     */
    public function testStandardDeviation() : void
    {
        $b = 3;

        self::assertEquals(\sqrt(2 * $b ** 2), LaplaceDistribution::getStandardDeviation($b));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LaplaceDistribution
     * @group framework
     */
    public function testMgf() : void
    {
        $t = 2;
        $b = 0.4;
        $m = 2;

        self::assertEquals(\exp($m * $t) / (1 - $b ** 2 * $t ** 2), LaplaceDistribution::getMgf($t, $m, $b));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\LaplaceDistribution
     * @group framework
     */
    public function testMgfException() : void
    {
        $this->expectException(\OutOfBoundsException::class);

        LaplaceDistribution::getMgf(3, 2, 4);
    }
}
