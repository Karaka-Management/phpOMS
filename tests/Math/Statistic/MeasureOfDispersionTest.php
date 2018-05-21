<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Math\Statistic;

use phpOMS\Math\Statistic\MeasureOfDispersion;

class MeasureOfDispersionTest extends \PHPUnit\Framework\TestCase
{
    public function testRange()
    {
        self::assertEquals((float) (9 - 1), MeasureOfDispersion::range([4, 5, 9, 1, 3]));
    }

    public function testStandardDeviation()
    {
        self::assertEquals(2.160246, MeasureOfDispersion::standardDeviation([1, 2, 3, 4, 5, 6, 7]), '', 0.01);
    }

    public function testEmpiricalCovariance()
    {
        self::assertEquals(
            4.667,
            MeasureOfDispersion::empiricalCovariance(
                [1, 2, 3, 4, 5, 6, 7],
                [3, 4, 5, 9, 7, 8, 9]
            ), '', 0.01
        );
    }

    public function testVariance()
    {
        self::assertEquals(6219.9, MeasureOfDispersion::sampleVariance([3, 21, 98, 203, 17, 9]), '', 0.01);
        self::assertEquals(5183.25, MeasureOfDispersion::empiricalVariance([3, 21, 98, 203, 17, 9]), '', 0.01);
    }

    public function testDeviation()
    {
        self::assertEquals(0.0, MeasureOfDispersion::meanDeviation([3, 4, 5, 9, 7, 8, 9]), '', 0.01);
        self::assertEquals(2.0816, MeasureOfDispersion::meanAbsoluteDeviation([3, 4, 5, 9, 7, 8, 9]), '', 0.01);
        self::assertEquals((12.96 + 2.56 + 0.36 + 5.76 + 11.56) / 5, MeasureOfDispersion::squaredMeanDeviation([1, 3, 4, 7, 8]), '', 0.01);
    }

    public function testEmpiricalVariationCoefficient()
    {
        self::assertEquals(0.5400, MeasureOfDispersion::empiricalVariationCoefficient([1, 2, 3, 4, 5, 6, 7]), '', 0.01);
    }

    /**
     * @expectedException phpOMS\Math\Exception\ZeroDevisionException
     */
    public function testInvalidEmpiricalVariationCoefficient()
    {
        MeasureOfDispersion::empiricalVariationCoefficient([1, 2, 3, 4, 5, 6, 7], 0);
    }

    /**
     * @expectedException phpOMS\Math\Exception\ZeroDevisionException
     */
    public function testInvalidEmpiricalCovariance()
    {
        MeasureOfDispersion::empiricalCovariance([], []);
    }

    /**
     * @expectedException phpOMS\Math\Matrix\Exception\InvalidDimensionException
     */
    public function testInvalidEmpiricalCovarianceDimension()
    {
        MeasureOfDispersion::empiricalCovariance([1, 2, 3, 4], [1, 2, 3]);
    }

    /**
     * @expectedException phpOMS\Math\Exception\ZeroDevisionException
     */
    public function testInvalidSampleVariance()
    {
        MeasureOfDispersion::sampleVariance([]);
    }

    /**
     * @expectedException phpOMS\Math\Exception\ZeroDevisionException
     */
    public function testInvalidEmpiricalVariance()
    {
        MeasureOfDispersion::empiricalVariance([]);
    }
}
