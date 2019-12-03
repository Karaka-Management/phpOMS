<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Statistic;

use phpOMS\Math\Statistic\Average;

/**
 * @testdox phpOMS\tests\Math\Statistic\AverageTest: Averages
 *
 * @internal
 */
class AverageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The average change of a dataset is correctly calculated
     * @group framework
     */
    public function testAverage() : void
    {
        self::assertEquals(-3 / 2, Average::averageDatasetChange([6, 7, 6, 3, 0]));
    }

    /**
     * @testdox The average mean of angles is calculated correctly
     * @group framework
     */
    public function testAngleMean() : void
    {
        self::assertEqualsWithDelta(-90, Average::angleMean([90.0, 180.0, 270.0, 360.0]), 0.01);
        self::assertEqualsWithDelta(9.999999999999977, Average::angleMean([370.0]), 0.01);

        self::assertEqualsWithDelta(270, Average::angleMean2([90.0, 180.0, 270.0, 360.0]), 0.01);
        self::assertEqualsWithDelta(9.999999999999977, Average::angleMean2([370.0]), 0.01);
    }

    /**
     * @testdox The arithmetic mean is correctly calculated
     * @group framework
     */
    public function testArithmeticMean() : void
    {
        self::assertEqualsWithDelta(4, Average::arithmeticMean([1, 2, 3, 4, 5, 6, 7]), 0.01);
    }

    /**
     * @testdox The weighted mean is correctly calculated
     * @group framework
     */
    public function testWeightedAverage() : void
    {
        self::assertEqualsWithDelta(69 / 20, Average::weightedAverage(
            [1, 2, 3, 4, 5, 6, 7],
            [0.1, 0.2, 0.3, 0.1, 0.2, 0.05, 0.05]
        ), 0.01);
    }

    /**
     * @testdox The geometric mean is correctly calculated
     * @group framework
     */
    public function testGeometricMean() : void
    {
        self::assertEqualsWithDelta(3.3800151591413, Average::geometricMean([1, 2, 3, 4, 5, 6, 7]), 0.01);
    }

    /**
     * @testdox The harmonic mean is correctly calculated
     * @group framework
     */
    public function testHarmonicMean() : void
    {
        self::assertEqualsWithDelta(2.6997245179063, Average::harmonicMean([1, 2, 3, 4, 5, 6, 7]), 0.01);
    }

    /**
     * @testdox The moving average is correctly calculated
     * @group framework
     */
    public function testMovingAverage() : void
    {
        $data = [
            67.5, 66.5, 66.44, 66.44, 66.25, 65.88, 66.63, 66.56, 65.63, 66.06,
            63.94, 64.13, 64.50, 62.81, 61.88, 62.50, 61.44, 60.13, 61.31, 61.38,
        ];

        $average = [66.39, 66.03, 65.79, 65.6, 65.24, 64.8, 64.46, 63.94, 63.3, 62.87, 62.4];

        self::assertEqualsWithDelta($average, Average::totalMovingAverage($data, 10), 0.1);
    }

    /**
     * @testdox Different weight and dataset dimensions throw a InvalidDimensionException
     * @group framework
     */
    public function testInvalidWeightedAverageDimension() : void
    {
        self::expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        Average::weightedAverage([1, 2, 3, 4, 5, 6, 7], [0.1, 0.2, 0.3, 0.1, 0.2, 0.05]);
    }

    /**
     * @testdox An empty dataset for the arithmetic mean throws a ZeroDevisionException
     * @group framework
     */
    public function testInvalidArithmeticMeanZeroDevision() : void
    {
        self::expectException(\phpOMS\Math\Exception\ZeroDevisionException::class);

        Average::arithmeticMean([]);
    }

    /**
     * @testdox An empty dataset for the moving average throws a Exception
     * @group framework
     */
    public function testInvalidMovingAverageZeroDevision() : void
    {
        self::expectException(\Exception::class);

        Average::movingAverage([], 4, 2);
    }

    /**
     * @testdox An empty dataset for the harmonic mean throws a ZeroDevisionException
     * @group framework
     */
    public function testInvalidHarmonicMeanZeroDevision() : void
    {
        self::expectException(\phpOMS\Math\Exception\ZeroDevisionException::class);

        Average::harmonicMean([]);
    }

    /**
     * @testdox An empty dataset for the geometric mean throws a ZeroDevisionException
     * @group framework
     */
    public function testInvalidGeometricMean() : void
    {
        self::expectException(\phpOMS\Math\Exception\ZeroDevisionException::class);

        Average::geometricMean([]);
    }

    /**
     * @testdox A dataset with a 0 element throws a ZeroDevisionException
     * @group framework
     */
    public function testInvalidHarmonicMean() : void
    {
        self::expectException(\phpOMS\Math\Exception\ZeroDevisionException::class);

        Average::harmonicMean([1, 2, 3, 0, 5, 6, 7]);
    }

    /**
     * @testdox The mode is correctly calculated
     * @group framework
     */
    public function testMode() : void
    {
        self::assertEqualsWithDelta(2, Average::mode([1, 2, 2, 3, 4, 4, 2]), 0.01);
    }

    /**
     * @testdox The median is correctly calculated
     * @group framework
     */
    public function testMedian() : void
    {
        self::assertEqualsWithDelta(4, Average::median([1, 2, 3, 4, 5, 6, 7]), 0.01);
        self::assertEqualsWithDelta(3.5, Average::median([1, 2, 3, 4, 5, 6]), 0.01);
    }
}
