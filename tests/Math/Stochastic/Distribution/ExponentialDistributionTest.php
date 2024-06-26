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

use phpOMS\Math\Stochastic\Distribution\ExponentialDistribution;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Stochastic\Distribution\ExponentialDistribution::class)]
final class ExponentialDistributionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testPdf() : void
    {
        $lambda = 0.1;
        $x      = 7;

        self::assertEqualsWithDelta(0.049659, ExponentialDistribution::getPdf($x, $lambda), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testCdf() : void
    {
        $lambda = 0.1;
        $x      = 7;

        self::assertEqualsWithDelta(0.5034, ExponentialDistribution::getCdf($x, $lambda), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMean() : void
    {
        self::assertEquals(1 / 3, ExponentialDistribution::getMean(3));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMode() : void
    {
        self::assertEquals(0, ExponentialDistribution::getMode());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMedian() : void
    {
        self::assertEquals(1 / 3 * \log(2), ExponentialDistribution::getMedian(3));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMgf() : void
    {
        $lambda = 3;
        $t      = 2;

        self::assertEquals($lambda / ($lambda - $t), ExponentialDistribution::getMgf($t, $lambda));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testVariance() : void
    {
        self::assertEquals(1 / (3 ** 2), ExponentialDistribution::getVariance(3));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStandardDeviation() : void
    {
        self::assertEquals(\sqrt(1 / (3 ** 2)), ExponentialDistribution::getStandardDeviation(3));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testExKurtosis() : void
    {
        self::assertEquals(6, ExponentialDistribution::getExKurtosis());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testSkewness() : void
    {
        self::assertEquals(2, ExponentialDistribution::getSkewness());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMgfException() : void
    {
        $this->expectException(\OutOfBoundsException::class);

        ExponentialDistribution::getMgf(3, 3);
    }
}
