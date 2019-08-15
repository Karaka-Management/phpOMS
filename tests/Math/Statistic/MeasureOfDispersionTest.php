<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Math\Statistic;

use phpOMS\Math\Statistic\MeasureOfDispersion;

/**
 * @internal
 */
class MeasureOfDispersionTest extends \PHPUnit\Framework\TestCase
{
    public function testRange() : void
    {
        self::assertEquals((float) (9 - 1), MeasureOfDispersion::range([4, 5, 9, 1, 3]));
    }

    public function testStandardDeviation() : void
    {
        self::assertEqualsWithDelta(2.160246, MeasureOfDispersion::standardDeviation([1, 2, 3, 4, 5, 6, 7]), 0.01);
    }

    public function testEmpiricalCovariance() : void
    {
        self::assertEqualsWithDelta(
            4.667,
            MeasureOfDispersion::empiricalCovariance(
                [1, 2, 3, 4, 5, 6, 7],
                [3, 4, 5, 9, 7, 8, 9]
            ), 0.01
        );
    }

    public function testVariance() : void
    {
        self::assertEqualsWithDelta(6219.9, MeasureOfDispersion::sampleVariance([3, 21, 98, 203, 17, 9]), 0.01);
        self::assertEqualsWithDelta(5183.25, MeasureOfDispersion::empiricalVariance([3, 21, 98, 203, 17, 9]), 0.01);
    }

    public function testDeviation() : void
    {
        self::assertEqualsWithDelta(0.0, MeasureOfDispersion::meanDeviation([3, 4, 5, 9, 7, 8, 9]), 0.01);
        self::assertEqualsWithDelta(2.0816, MeasureOfDispersion::meanAbsoluteDeviation([3, 4, 5, 9, 7, 8, 9]), 0.01);
        self::assertEqualsWithDelta((12.96 + 2.56 + 0.36 + 5.76 + 11.56) / 5, MeasureOfDispersion::squaredMeanDeviation([1, 3, 4, 7, 8]), 0.01);
    }

    public function testEmpiricalVariationCoefficient() : void
    {
        self::assertEqualsWithDelta(0.5400, MeasureOfDispersion::empiricalVariationCoefficient([1, 2, 3, 4, 5, 6, 7]), 0.01);
    }

    public function testIQR() : void
    {
        $x = [7, 7, 31, 31, 47, 75, 87, 115, 116, 119, 119, 155, 177];
        self::assertEquals(88, MeasureOfDispersion::getIQR($x));
    }

    public function testInvalidEmpiricalVariationCoefficient() : void
    {
        self::expectException(\phpOMS\Math\Exception\ZeroDevisionException::class);

        MeasureOfDispersion::empiricalVariationCoefficient([1, 2, 3, 4, 5, 6, 7], 0);
    }

    public function testInvalidEmpiricalCovariance() : void
    {
        self::expectException(\phpOMS\Math\Exception\ZeroDevisionException::class);

        MeasureOfDispersion::empiricalCovariance([], []);
    }

    public function testInvalidEmpiricalCovarianceDimension() : void
    {
        self::expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        MeasureOfDispersion::empiricalCovariance([1, 2, 3, 4], [1, 2, 3]);
    }

    public function testInvalidSampleVariance() : void
    {
        self::expectException(\phpOMS\Math\Exception\ZeroDevisionException::class);

        MeasureOfDispersion::sampleVariance([]);
    }

    public function testInvalidEmpiricalVariance() : void
    {
        self::expectException(\phpOMS\Math\Exception\ZeroDevisionException::class);

        MeasureOfDispersion::empiricalVariance([]);
    }
}
