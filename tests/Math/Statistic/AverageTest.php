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

namespace phpOMS\tests\Math\Statistic;

use phpOMS\Math\Statistic\Average;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Statistic\Average::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Statistic\AverageTest: Averages')]
final class AverageTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The average change of a dataset is correctly calculated')]
    public function testAverage() : void
    {
        self::assertEquals(-3 / 2, Average::averageDatasetChange([6, 7, 6, 3, 0]));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The average mean of angles is calculated correctly')]
    public function testAngleMean() : void
    {
        self::assertEqualsWithDelta(-90, Average::angleMean([90.0, 180.0, 270.0, 360.0]), 0.01);
        self::assertEqualsWithDelta(-90, Average::angleMean([90.0, 45.0, 180.0, 270.0, 360.0, 360.0], 1), 0.01);
        self::assertEqualsWithDelta(9.999999999999977, Average::angleMean([370.0]), 0.01);

        self::assertEqualsWithDelta(270, Average::angleMean2([90.0, 180.0, 270.0, 360.0]), 0.01);
        self::assertEqualsWithDelta(270, Average::angleMean2([90.0, 45.0, 180.0, 270.0, 360.0, 360.0], 1), 0.01);
        self::assertEqualsWithDelta(9.999999999999977, Average::angleMean2([370.0]), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The arithmetic mean is correctly calculated')]
    public function testArithmeticMean() : void
    {
        self::assertEqualsWithDelta(4, Average::arithmeticMean([1, 2, 3, 4, 5, 6, 7]), 0.01);
        self::assertEqualsWithDelta(4, Average::arithmeticMean([1, 2, -4, -6, 8, 9, 3, 4, 5, 6, 7], 2), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The weighted mean is correctly calculated')]
    public function testWeightedAverage() : void
    {
        self::assertEqualsWithDelta(69 / 20, Average::weightedAverage(
            [1, 2, 3, 4, 5, 6, 7],
            [0.1, 0.2, 0.3, 0.1, 0.2, 0.05, 0.05]
        ), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The geometric mean is correctly calculated')]
    public function testGeometricMean() : void
    {
        self::assertEqualsWithDelta(3.3800151591413, Average::geometricMean([1, 2, 3, 4, 5, 6, 7]), 0.01);
        self::assertEqualsWithDelta(3.3800151591413, Average::geometricMean([1, 2, -4, -6, 8, 9, 3, 4, 5, 6, 7],2), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The harmonic mean is correctly calculated')]
    public function testHarmonicMean() : void
    {
        self::assertEqualsWithDelta(2.6997245179063, Average::harmonicMean([1, 2, 3, 4, 5, 6, 7]), 0.01);
        self::assertEqualsWithDelta(2.6997245179063, Average::harmonicMean([1, 2, -4, -6, 8, 9, 3, 4, 5, 6, 7], 2), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The moving average is correctly calculated')]
    public function testMovingAverage() : void
    {
        $data = [
            67.5, 66.5, 66.44, 66.44, 66.25, 65.88, 66.63, 66.56, 65.63, 66.06,
            63.94, 64.13, 64.50, 62.81, 61.88, 62.50, 61.44, 60.13, 61.31, 61.38,
        ];

        $average = [
            66.626, 66.302, 66.328, 66.352, 66.19, 66.152, 65.764, 65.264, 64.852, 64.288, 63.452, 63.164, 62.626, 61.752, 61.452, 61.352,
        ];

        self::assertEqualsWithDelta($average, Average::totalMovingAverage($data, 5), 0.1);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The weighted moving average is correctly calculated')]
    public function testWeightedMovingAverage() : void
    {
        $data    = [67.5, 66.5, 66.44, 66.44, 66.25, 65.88, 66.63];
        $weights = [0.1, 0.2, 0.3, 0.1, 0.2, 0.05, 0.05];
        $average = [39.982, 39.876, 39.826, 23.188, 19.876];

        self::assertEqualsWithDelta($average, Average::totalMovingAverage($data, 3, $weights), 0.1);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different weight and dataset dimensions throw a InvalidDimensionException')]
    public function testInvalidWeightedAverageDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        Average::weightedAverage([1, 2, 3, 4, 5, 6, 7], [0.1, 0.2, 0.3, 0.1, 0.2, 0.05]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An empty dataset for the arithmetic mean throws a ZeroDivisionException')]
    public function testInvalidArithmeticMeanZeroDivision() : void
    {
        $this->expectException(\phpOMS\Math\Exception\ZeroDivisionException::class);

        Average::arithmeticMean([]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An empty dataset for the moving average throws a Exception')]
    public function testInvalidMovingAverageZeroDivision() : void
    {
        $this->expectException(\Exception::class);

        Average::movingAverage([], 4, 2);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An empty dataset for the harmonic mean throws a ZeroDivisionException')]
    public function testInvalidHarmonicMeanZeroDivision() : void
    {
        $this->expectException(\phpOMS\Math\Exception\ZeroDivisionException::class);

        Average::harmonicMean([]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An empty dataset for the geometric mean throws a ZeroDivisionException')]
    public function testInvalidGeometricMean() : void
    {
        $this->expectException(\phpOMS\Math\Exception\ZeroDivisionException::class);

        Average::geometricMean([]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testInvalidAngleMean() : void
    {
        $this->expectException(\phpOMS\Math\Exception\ZeroDivisionException::class);

        Average::angleMean([]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testInvalidAngleMean2() : void
    {
        $this->expectException(\phpOMS\Math\Exception\ZeroDivisionException::class);

        Average::angleMean2([]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A dataset with a 0 element throws a ZeroDivisionException')]
    public function testInvalidHarmonicMean() : void
    {
        $this->expectException(\phpOMS\Math\Exception\ZeroDivisionException::class);

        Average::harmonicMean([1, 2, 3, 0, 5, 6, 7]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The mode is correctly calculated')]
    public function testMode() : void
    {
        self::assertEqualsWithDelta(2, Average::mode([1, 2, 2, 3, 4, 4, 2]), 0.01);
        self::assertEqualsWithDelta(2, Average::mode([1, 2, 2, -1, -5, 3, 4, 4, 5, 9, 2], 2), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The median is correctly calculated')]
    public function testMedian() : void
    {
        self::assertEqualsWithDelta(4, Average::median([1, 2, 3, 4, 5, 6, 7]), 0.01);
        self::assertEqualsWithDelta(4, Average::median([1, 2, -4, -6, 8, 9, 3, 4, 5, 6, 7], 2), 0.01);
        self::assertEqualsWithDelta(3.5, Average::median([1, 2, 3, 4, 5, 6]), 0.01);
    }
}
