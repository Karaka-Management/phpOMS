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

use phpOMS\Math\Stochastic\Distribution\UniformDistributionDiscrete;

/**
 * @internal
 */
final class UniformDistributionDiscreteTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \phpOMS\Math\Stochastic\Distribution\UniformDistributionDiscrete
     * @group framework
     */
    public function testPmf() : void
    {
        $a = 1;
        $b = 4;

        self::assertEquals(1 / ($b - $a + 1), UniformDistributionDiscrete::getPmf($a, $b));
    }

    /**
     * @covers \phpOMS\Math\Stochastic\Distribution\UniformDistributionDiscrete
     * @group framework
     */
    public function testCdf() : void
    {
        $a = 1;
        $b = 4;
        $k = 3;

        self::assertEquals(($k - $a + 1) / ($b - $a + 1), UniformDistributionDiscrete::getCdf($k, $a, $b));
    }

    /**
     * @covers \phpOMS\Math\Stochastic\Distribution\UniformDistributionDiscrete
     * @group framework
     */
    public function testSkewness() : void
    {
        self::assertEquals(0, UniformDistributionDiscrete::getSkewness());
    }

    /**
     * @covers \phpOMS\Math\Stochastic\Distribution\UniformDistributionDiscrete
     * @group framework
     */
    public function testMean() : void
    {
        $a = 1;
        $b = 4;

        self::assertEquals(1 / 2 * ($a + $b), UniformDistributionDiscrete::getMean($a, $b));
    }

    /**
     * @covers \phpOMS\Math\Stochastic\Distribution\UniformDistributionDiscrete
     * @group framework
     */
    public function testMedian() : void
    {
        $a = 1;
        $b = 4;

        self::assertEquals(1 / 2 * ($a + $b), UniformDistributionDiscrete::getMedian($a, $b));
    }

    /**
     * @covers \phpOMS\Math\Stochastic\Distribution\UniformDistributionDiscrete
     * @group framework
     */
    public function testVariance() : void
    {
        $a = 1;
        $b = 4;

        self::assertEquals((($b - $a + 1) ** 2 - 1) / 12, UniformDistributionDiscrete::getVariance($a, $b));
    }

    /**
     * @covers \phpOMS\Math\Stochastic\Distribution\UniformDistributionDiscrete
     * @group framework
     */
    public function testStandardDeviation() : void
    {
        $a = 1;
        $b = 4;

        self::assertEquals(\sqrt((($b - $a + 1) ** 2 - 1) / 12), UniformDistributionDiscrete::getStandardDeviation($a, $b));
    }

    /**
     * @covers \phpOMS\Math\Stochastic\Distribution\UniformDistributionDiscrete
     * @group framework
     */
    public function testExKurtosis() : void
    {
        $a = 1;
        $b = 4;
        $n = $b - $a + 1;

        self::assertEqualsWithDelta(
            -(6 * ($n ** 2 + 1)) / (5 * ($n ** 2 - 1)),
            UniformDistributionDiscrete::getExKurtosis($a, $b), 0.1
        );
    }

    /**
     * @covers \phpOMS\Math\Stochastic\Distribution\UniformDistributionDiscrete
     * @group framework
     */
    public function testMgf() : void
    {
        self::assertEquals(
            (\exp(3 * 2) - \exp((4 + 1) * 2)) / ((4 - 3 + 1) * (1 - \exp(2))),
            UniformDistributionDiscrete::getMgf(2, 3, 4)
        );
    }

    /**
     * @covers \phpOMS\Math\Stochastic\Distribution\UniformDistributionDiscrete
     * @group framework
     */
    public function testCdfExceptionUpper() : void
    {
        $this->expectException(\OutOfBoundsException::class);

        UniformDistributionDiscrete::getCdf(5, 2, 4);
    }

    /**
     * @covers \phpOMS\Math\Stochastic\Distribution\UniformDistributionDiscrete
     * @group framework
     */
    public function testCdfExceptionLower() : void
    {
        $this->expectException(\OutOfBoundsException::class);

        UniformDistributionDiscrete::getCdf(1, 2, 4);
    }
}
