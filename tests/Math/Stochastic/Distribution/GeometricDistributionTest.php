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

use phpOMS\Math\Stochastic\Distribution\GeometricDistribution;

/**
 * @internal
 */
final class GeometricDistributionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Math\Stochastic\Distribution\GeometricDistribution
     * @group framework
     */
    public function testPmf() : void
    {
        $p = 0.2;
        $k = 4;

        self::assertEqualsWithDelta(0.1024, GeometricDistribution::getPmf($p, $k), 0.01);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\GeometricDistribution
     * @group framework
     */
    public function testCdf() : void
    {
        $p = 0.2;
        $k = 6;

        // P(X > 6) = P(X <= 6) => 1 - CDF
        self::assertEqualsWithDelta(0.262, 1 - GeometricDistribution::getCdf($p, $k), 0.01);
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\GeometricDistribution
     * @group framework
     */
    public function testMode() : void
    {
        self::assertEquals(1, GeometricDistribution::getMode());
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\GeometricDistribution
     * @group framework
     */
    public function testMean() : void
    {
        $p = 0.3;
        self::assertEquals(1 / $p, GeometricDistribution::getMean($p));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\GeometricDistribution
     * @group framework
     */
    public function testVariance() : void
    {
        $p = 0.3;

        self::assertEquals((1 - $p) / $p ** 2, GeometricDistribution::getVariance($p));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\GeometricDistribution
     * @group framework
     */
    public function testgetStandardDeviation() : void
    {
        $p = 0.3;

        self::assertEquals(\sqrt((1 - $p) / $p ** 2), GeometricDistribution::getStandardDeviation($p));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\GeometricDistribution
     * @group framework
     */
    public function testSkewness() : void
    {
        $p = 0.3;

        self::assertEquals((2 - $p) / \sqrt(1 - $p), GeometricDistribution::getSkewness($p));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\GeometricDistribution
     * @group framework
     */
    public function testExKurtosis() : void
    {
        $p = 0.3;

        self::assertEquals(6 + ($p ** 2) / (1 - $p), GeometricDistribution::getExKurtosis($p));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\GeometricDistribution
     * @group framework
     */
    public function testMedian() : void
    {
        $p = 0.3;

        self::assertEquals(\ceil(-1 / \log(1 - $p, 2)), GeometricDistribution::getMedian($p));
    }

    /**
     * @covers phpOMS\Math\Stochastic\Distribution\GeometricDistribution
     * @group framework
     */
    public function testMgf() : void
    {
        $p  = 0.3;
        $t1 = 2;
        $t2 = -\log(1 - $p) * 0.8;

        self::assertEquals($p / (1 - (1 - $p) * \exp($t1)), GeometricDistribution::getMgf($p, $t1));
        self::assertEquals($p * \exp($t2) / (1 - (1 - $p) * \exp($t2)), GeometricDistribution::getMgf($p, $t2));
    }
}
