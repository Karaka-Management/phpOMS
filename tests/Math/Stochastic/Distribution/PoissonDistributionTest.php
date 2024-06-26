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

use phpOMS\Math\Stochastic\Distribution\PoissonDistribution;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Stochastic\Distribution\PoissonDistribution::class)]
final class PoissonDistributionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testPmf() : void
    {
        $k = 4;
        $l = 3;

        self::assertEqualsWithDelta(0.16803, PoissonDistribution::getPmf(4, 3), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testCdf() : void
    {
        $k = 4;
        $l = 3;

        self::assertEqualsWithDelta(0.81526, PoissonDistribution::getCdf(4, 3), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMode() : void
    {
        $l = 4.6;

        self::assertEqualsWithDelta(4, PoissonDistribution::getMode($l), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMean() : void
    {
        $l = 4.6;

        self::assertEquals($l, PoissonDistribution::getMean($l));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testVariance() : void
    {
        $l = 4.6;

        self::assertEquals($l, PoissonDistribution::getVariance($l));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStandardDeviation() : void
    {
        $l = 4.6;

        self::assertEquals(\sqrt($l), PoissonDistribution::getStandardDeviation($l));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testSkewness() : void
    {
        $l = 4.6;

        self::assertEquals(1 / \sqrt($l), PoissonDistribution::getSkewness($l));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testExKurtosis() : void
    {
        $l = 4.6;

        self::assertEquals(1 / $l, PoissonDistribution::getExKurtosis($l));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMedian() : void
    {
        $l = 4.6;

        self::assertEquals(\floor($l + 1 / 3 - 0.02 / $l), PoissonDistribution::getMedian($l));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testFisherInformation() : void
    {
        $l = 4.6;

        self::assertEquals(1 / $l, PoissonDistribution::getFisherInformation($l));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMgf() : void
    {
        $l = 4.6;
        $t = 3;

        self::assertEquals(\exp($l * (\exp($t) - 1)), PoissonDistribution::getMgf($l, $t));
    }
}
