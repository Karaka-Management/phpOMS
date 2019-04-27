<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
 declare(strict_types=1);

namespace phpOMS\tests\Math\Statistic;

use phpOMS\Math\Statistic\Average;

/**
 * @internal
 */
class AverageTest extends \PHPUnit\Framework\TestCase
{
    public function testAverage() : void
    {
        self::assertEquals(-3 / 2, Average::averageDatasetChange([6, 7, 6, 3, 0]));
    }

    public function testAngleMean() : void
    {
        self::assertEqualsWithDelta(-90, Average::angleMean([90.0, 180.0, 270.0, 360.0]), 0.01);
        self::assertEqualsWithDelta(9.999999999999977, Average::angleMean([370.0]), 0.01);

        self::assertEqualsWithDelta(270, Average::angleMean2([90.0, 180.0, 270.0, 360.0]), 0.01);
        self::assertEqualsWithDelta(9.999999999999977, Average::angleMean2([370.0]), 0.01);
    }

    public function testArithmeticMean() : void
    {
        self::assertEqualsWithDelta(4, Average::arithmeticMean([1, 2, 3, 4, 5, 6, 7]), 0.01);
    }

    public function testWeightedAverage() : void
    {
        self::assertEqualsWithDelta(69 / 20, Average::weightedAverage(
            [1, 2, 3, 4, 5, 6, 7],
            [0.1, 0.2, 0.3, 0.1, 0.2, 0.05, 0.05]
        ), 0.01);
    }

    public function testGeometricMean() : void
    {
        self::assertEqualsWithDelta(3.3800151591413, Average::geometricMean([1, 2, 3, 4, 5, 6, 7]), 0.01);
    }

    public function testHarmonicMean() : void
    {
        self::assertEqualsWithDelta(2.6997245179063, Average::harmonicMean([1, 2, 3, 4, 5, 6, 7]), 0.01);
    }

    public function testMovingAverage() : void
    {
        $data = [
            67.5, 66.5, 66.44, 66.44, 66.25, 65.88, 66.63, 66.56, 65.63, 66.06,
            63.94, 64.13, 64.50, 62.81, 61.88, 62.50, 61.44, 60.13, 61.31, 61.38,
        ];

        $average = [66.39, 66.03, 65.79, 65.6, 65.24, 64.8, 64.46, 63.94, 63.3, 62.87, 62.4];

        self::assertEqualsWithDelta($average, Average::totalMovingAverage($data, 10), 0.1);
    }

    public function testInvalidWeightedAverageDimension() : void
    {
        self::expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        Average::weightedAverage([1, 2, 3, 4, 5, 6, 7], [0.1, 0.2, 0.3, 0.1, 0.2, 0.05]);
    }

    public function testInvalidArithmeticMeanZeroDevision() : void
    {
        self::expectException(\phpOMS\Math\Exception\ZeroDevisionException::class);

        Average::arithmeticMean([]);
    }

    public function testInvalidGeometricMean() : void
    {
        self::expectException(\phpOMS\Math\Exception\ZeroDevisionException::class);

        Average::geometricMean([]);
    }

    public function testInvalidHarmonicMean() : void
    {
        self::expectException(\phpOMS\Math\Exception\ZeroDevisionException::class);

        Average::harmonicMean([1, 2, 3, 0, 5, 6, 7]);
    }

    public function testMode() : void
    {
        self::assertEqualsWithDelta(2, Average::mode([1, 2, 2, 3, 4, 4, 2]), 0.01);
    }

    public function testMedia() : void
    {
        self::assertEqualsWithDelta(4, Average::median([1, 2, 3, 4, 5, 6, 7]), 0.01);
        self::assertEqualsWithDelta(3.5, Average::median([1, 2, 3, 4, 5, 6]), 0.01);
    }
}
