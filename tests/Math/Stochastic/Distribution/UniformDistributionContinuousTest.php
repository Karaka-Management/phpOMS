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

use phpOMS\Math\Stochastic\Distribution\UniformDistributionContinuous;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Stochastic\Distribution\UniformDistributionContinuous::class)]
final class UniformDistributionContinuousTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testPdf() : void
    {
        $a = 1;
        $b = 4;

        self::assertEquals(1 / ($b - $a), UniformDistributionContinuous::getPdf(3, $a, $b));
        self::assertEquals(0, UniformDistributionContinuous::getPdf(0, $a, $b));
        self::assertEquals(0, UniformDistributionContinuous::getPdf(5, $a, $b));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testCdf() : void
    {
        $a = 1;
        $b = 4;
        $x = 3;

        self::assertEquals(($x - $a) / ($b - $a), UniformDistributionContinuous::getCdf($x, $a, $b));
        self::assertEquals(0, UniformDistributionContinuous::getCdf(0, $a, $b));
        self::assertEquals(1, UniformDistributionContinuous::getCdf(5, $a, $b));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMode() : void
    {
        $a = 1;
        $b = 4;

        self::assertThat(
            UniformDistributionContinuous::getMode($a, $b),
            self::logicalAnd(
                self::greaterThan($a),
                self::lessThan($b)
            )
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMean() : void
    {
        $a = 1;
        $b = 4;

        self::assertEquals(1 / 2 * ($b + $a), UniformDistributionContinuous::getMean($a, $b));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMedian() : void
    {
        $a = 1;
        $b = 4;

        self::assertEquals(1 / 2 * ($b + $a), UniformDistributionContinuous::getMedian($a, $b));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testVariance() : void
    {
        $a = 1;
        $b = 4;

        self::assertEquals(1 / 12 * ($b - $a) ** 2, UniformDistributionContinuous::getVariance($a, $b));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStandardDeviation() : void
    {
        $a = 1;
        $b = 4;

        self::assertEquals(\sqrt(1 / 12 * ($b - $a) ** 2), UniformDistributionContinuous::getStandardDeviation($a, $b));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testSkewness() : void
    {
        self::assertEquals(0, UniformDistributionContinuous::getSkewness());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testExKurtosis() : void
    {
        self::assertEquals(-6 / 5, UniformDistributionContinuous::getExKurtosis());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMgf() : void
    {
        self::assertEquals(1, UniformDistributionContinuous::getMgf(0, 2, 3));
        self::assertEquals(
            (\exp(2 * 4) - \exp(2 * 3)) / (2 * (4 - 3)),
            UniformDistributionContinuous::getMgf(2, 3, 4)
        );
    }
}
