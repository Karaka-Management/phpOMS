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

namespace phpOMS\tests\Math\Statistic;

use phpOMS\Math\Statistic\MeasureOfDispersion;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Statistic\MeasureOfDispersion::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Statistic\MeasureOfDispersionTest: Measure of dispersion')]
final class MeasureOfDispersionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The range of a dataset is correctly calculated')]
    public function testRange() : void
    {
        self::assertEquals((float) (9 - 1), MeasureOfDispersion::range([4, 5, 9, 1, 3]));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The standard deviation is correctly calculated')]
    public function testStandardDeviationSample() : void
    {
        self::assertEqualsWithDelta(2.160246, MeasureOfDispersion::standardDeviationSample([1, 2, 3, 4, 5, 6, 7]), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The empirical covariance is correctly calculated')]
    public function testEmpiricalCovariance() : void
    {
        self::assertEqualsWithDelta(
            4.0,
            MeasureOfDispersion::empiricalCovariance(
                [1, 2, 3, 4, 5, 6, 7],
                [3, 4, 5, 9, 7, 8, 9]
            ), 0.01
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The empirical covariance on a sample is correctly calculated')]
    public function testSampleCovariance() : void
    {
        self::assertEqualsWithDelta(
            4.667,
            MeasureOfDispersion::sampleCovariance(
                [1, 2, 3, 4, 5, 6, 7],
                [3, 4, 5, 9, 7, 8, 9]
            ), 0.01
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The sample variance is correctly calculated')]
    public function testVarianceSample() : void
    {
        self::assertEqualsWithDelta(6219.9, MeasureOfDispersion::sampleVariance([3, 21, 98, 203, 17, 9]), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The population/empirical variance is correctly calculated')]
    public function testVariancePopulation() : void
    {
        self::assertEqualsWithDelta(5183.25, MeasureOfDispersion::empiricalVariance([3, 21, 98, 203, 17, 9]), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The mean deviations are correctly calculated')]
    public function testDeviation() : void
    {
        self::assertEqualsWithDelta(0.0, MeasureOfDispersion::meanDeviation([3, 4, 5, 9, 7, 8, 9]), 0.01);
        self::assertEqualsWithDelta(2.0816, MeasureOfDispersion::meanAbsoluteDeviation([3, 4, 5, 9, 7, 8, 9]), 0.01);
        self::assertEqualsWithDelta((12.96 + 2.56 + 0.36 + 5.76 + 11.56) / 5, MeasureOfDispersion::squaredMeanDeviation([1, 3, 4, 7, 8]), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The mean deviations for every dataset element is correctly calculated')]
    public function testDeviationArray() : void
    {
        self::assertEqualsWithDelta(
            [0.13, -0.27, -0.37, 2.23, -0.57, -0.27, -0.97, -0.47, 0.33, 0.23],
            MeasureOfDispersion::meanDeviationArray([99.0, 98.6, 98.5, 101.1, 98.3, 98.6, 97.9, 98.4, 99.2, 99.1]),
            0.01
        );

        self::assertEqualsWithDelta(
            [0.13, 0.27, 0.37, 2.23, 0.57, 0.27, 0.97, 0.47, 0.33, 0.23],
            MeasureOfDispersion::meanAbsoluteDeviationArray([99.0, 98.6, 98.5, 101.1, 98.3, 98.6, 97.9, 98.4, 99.2, 99.1]),
            0.01
        );

        self::assertEqualsWithDelta(
            [0.0169, 0.0729, 0.1369, 4.9729, 0.3249, 0.0729, 0.9409, 0.2209, 0.1089, 0.0529],
            MeasureOfDispersion::squaredMeanDeviationArray([99.0, 98.6, 98.5, 101.1, 98.3, 98.6, 97.9, 98.4, 99.2, 99.1]),
            0.01
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The empirical variation coefficient is correctly calculated')]
    public function testEmpiricalVariationCoefficient() : void
    {
        self::assertEqualsWithDelta(0.5400, MeasureOfDispersion::empiricalVariationCoefficient([1, 2, 3, 4, 5, 6, 7]), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The interquartile range is correctly calculated')]
    public function testIQR() : void
    {
        $x = [7, 7, 31, 31, 47, 75, 87, 115, 116, 119, 119, 155, 177];
        self::assertEquals(88, MeasureOfDispersion::getIQR($x));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The empirical variation coefficient with a mean of 0 throws a ZeroDivisionException')]
    public function testInvalidEmpiricalVariationCoefficient() : void
    {
        $this->expectException(\phpOMS\Math\Exception\ZeroDivisionException::class);

        MeasureOfDispersion::empiricalVariationCoefficient([1, 2, 3, 4, 5, 6, 7], 0);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An empty dataset in the empirical covariance throws a ZeroDivisionException')]
    public function testInvalidEmpiricalCovariance() : void
    {
        $this->expectException(\phpOMS\Math\Exception\ZeroDivisionException::class);

        MeasureOfDispersion::empiricalCovariance([], []);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different dataset sizes in the empirical covariance throw a InvalidDimensionException')]
    public function testInvalidEmpiricalCovarianceDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MeasureOfDispersion::empiricalCovariance([1, 2, 3, 4], [1, 2, 3]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An empty dataset in the sample variance throws a ZeroDivisionException')]
    public function testInvalidSampleVariance() : void
    {
        $this->expectException(\phpOMS\Math\Exception\ZeroDivisionException::class);

        MeasureOfDispersion::sampleVariance([]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An empty dataset in the empirical/population variance throws a ZeroDivisionException')]
    public function testInvalidEmpiricalVariance() : void
    {
        $this->expectException(\phpOMS\Math\Exception\ZeroDivisionException::class);

        MeasureOfDispersion::empiricalVariance([]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testInvalidSampleCovarianceDimension() : void
    {
        $this->expectException(\phpOMS\Math\Exception\ZeroDivisionException::class);

        MeasureOfDispersion::sampleCovariance([], []);
    }
}
