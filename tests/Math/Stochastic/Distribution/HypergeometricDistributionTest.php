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

use phpOMS\Math\Stochastic\Distribution\HypergeometricDistribution;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Stochastic\Distribution\HypergeometricDistribution::class)]
final class HypergeometricDistributionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMean() : void
    {
        self::assertEquals(9, HypergeometricDistribution::getMean(15, 20, 12));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testVariance() : void
    {
        self::assertEqualsWithDelta(0.973328526784575 ** 2, HypergeometricDistribution::getVariance(15, 20, 12), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStandardDeviation() : void
    {
        self::assertEqualsWithDelta(0.973328526784575, HypergeometricDistribution::getStandardDeviation(15, 20, 12), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testSkewness() : void
    {
        self::assertEqualsWithDelta(0.114156, HypergeometricDistribution::getSkewness(15, 20, 12), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testExKurtosis() : void
    {
        self::assertEqualsWithDelta(-0.247277, HypergeometricDistribution::getExKurtosis(15, 20, 12), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMode() : void
    {
        $N = 8;
        $n = 4;
        $K = 5;

        self::assertEquals(3, HypergeometricDistribution::getMode($K, $N, $n));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testPmf() : void
    {
        self::assertEqualsWithDelta(0.146284, HypergeometricDistribution::getPmf(7, 20, 5, 10), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testCdf() : void
    {
        self::assertEqualsWithDelta(0.97136, HypergeometricDistribution::getCdf(7, 20, 5, 10), 0.001);
    }
}
