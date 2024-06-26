<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Stochastic\Distribution\TDistribution;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Stochastic\Distribution\TDistribution::class)]
final class TDistributionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMean() : void
    {
        self::assertEquals(0, TDistribution::getMean());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMedian() : void
    {
        self::assertEquals(0, TDistribution::getMedian());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMode() : void
    {
        self::assertEquals(0, TDistribution::getMode());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testVariance() : void
    {
        self::assertEqualsWithDelta(5 / 3, TDistribution::getVariance(5), 0.001);
        self::assertEqualsWithDelta(\PHP_FLOAT_MAX, TDistribution::getVariance(2), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStandardDeviation() : void
    {
        self::assertEqualsWithDelta(\sqrt(5 / 3), TDistribution::getStandardDeviation(5), 0.001);
        self::assertEqualsWithDelta(\PHP_FLOAT_MAX, TDistribution::getStandardDeviation(2), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testExKurtosis() : void
    {
        self::assertEqualsWithDelta(6, TDistribution::getExKurtosis(5), 0.001);
        self::assertEqualsWithDelta(\PHP_FLOAT_MAX, TDistribution::getExKurtosis(3), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testSkewness() : void
    {
        self::assertEquals(0, TDistribution::getSkewness());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testCdf() : void
    {
        self::assertEqualsWithDelta(0.0, TDistribution::getCdf(1.25, 5, 0), 0.001);
        self::assertEqualsWithDelta(0.86669, TDistribution::getCdf(1.25, 5, 1), 0.001);
        self::assertEqualsWithDelta(0.78867, TDistribution::getCdf(1.0, 2, 1), 0.001);
        self::assertEqualsWithDelta(0.4226, TDistribution::getCdf(1.0, 2, 2), 0.001);
    }
}
