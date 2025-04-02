<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Stochastic\Distribution\LaplaceDistribution;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Stochastic\Distribution\LaplaceDistribution::class)]
final class LaplaceDistributionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testPdf() : void
    {
        $x = 2;
        $m = 1;
        $b = 0.7;

        self::assertEqualsWithDelta(0.17118, LaplaceDistribution::getPdf($x, $m, $b), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testCdf() : void
    {
        $x = 2;
        $m = 1;
        $b = 0.7;

        self::assertEqualsWithDelta(0.88017, LaplaceDistribution::getCdf($x, $m, $b), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMode() : void
    {
        self::assertEquals(2, LaplaceDistribution::getMode(2));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMean() : void
    {
        self::assertEquals(2, LaplaceDistribution::getMean(2));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMedian() : void
    {
        self::assertEquals(2, LaplaceDistribution::getMedian(2));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testExKurtosis() : void
    {
        self::assertEquals(3, LaplaceDistribution::getExKurtosis());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testSkewness() : void
    {
        self::assertEquals(0, LaplaceDistribution::getSkewness());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testVariance() : void
    {
        $b = 3;

        self::assertEquals(2 * $b ** 2, LaplaceDistribution::getVariance($b));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStandardDeviation() : void
    {
        $b = 3;

        self::assertEquals(\sqrt(2 * $b ** 2), LaplaceDistribution::getStandardDeviation($b));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMgf() : void
    {
        $t = 2;
        $b = 0.4;
        $m = 2;

        self::assertEquals(\exp($m * $t) / (1 - $b ** 2 * $t ** 2), LaplaceDistribution::getMgf($t, $m, $b));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMgfException() : void
    {
        $this->expectException(\OutOfBoundsException::class);

        LaplaceDistribution::getMgf(3, 2, 4);
    }
}
