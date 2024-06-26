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

use phpOMS\Math\Stochastic\Distribution\BinomialDistribution;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Stochastic\Distribution\BinomialDistribution::class)]
final class BinomialDistributionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testPmf() : void
    {
        $p = 0.4;
        $n = 20;
        $k = 7;

        self::assertEqualsWithDelta(0.1659, BinomialDistribution::getPmf($n, $k, $p), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testCdf() : void
    {
        $p = 0.4;
        $n = 20;
        $k = 7;

        self::assertEqualsWithDelta(0.25, BinomialDistribution::getCdf($n, $k, $p), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMean() : void
    {
        $n = 20;
        $p = 0.4;

        self::assertEqualsWithDelta($n * $p, BinomialDistribution::getMean($n, $p), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMedian() : void
    {
        $n = 20;
        $p = 0.4;

        self::assertEqualsWithDelta(\floor($n * $p), BinomialDistribution::getMedian($n, $p), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMode() : void
    {
        $n = 20;
        $p = 0.4;

        self::assertEqualsWithDelta(\floor(($n + 1) * $p), BinomialDistribution::getMode($n, $p), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testVariance() : void
    {
        $n = 20;
        $p = 0.4;

        self::assertEqualsWithDelta($n * $p * (1 - $p), BinomialDistribution::getVariance($n, $p), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStandardDeviation() : void
    {
        $n = 20;
        $p = 0.4;

        self::assertEqualsWithDelta(\sqrt($n * $p * (1 - $p)), BinomialDistribution::getStandardDeviation($n, $p), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testSkewness() : void
    {
        $n = 20;
        $p = 0.4;

        self::assertEqualsWithDelta((1 - 2 * $p) / \sqrt($n * $p * (1 - $p)), BinomialDistribution::getSkewness($n, $p), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testExKurtosis() : void
    {
        $n = 20;
        $p = 0.4;

        self::assertEqualsWithDelta((1 - 6 * $p * (1 - $p)) / ($n * $p * (1 - $p)), BinomialDistribution::getExKurtosis($n, $p), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMgf() : void
    {
        $n = 20;
        $p = 0.4;
        $t = 3;

        self::assertEqualsWithDelta((1 - $p + $p * \exp($t)) ** $n, BinomialDistribution::getMgf($n, $t, $p), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testFisherInformation() : void
    {
        $n = 20;
        $p = 0.4;

        self::assertEqualsWithDelta($n / ($p * (1 - $p)), BinomialDistribution::getFisherInformation($n, $p), 0.01);
    }
}
