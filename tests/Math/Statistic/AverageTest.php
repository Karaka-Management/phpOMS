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

namespace phpOMS\tests\Math\Statistic;

use phpOMS\Math\Statistic\Average;

class AverageTest extends \PHPUnit\Framework\TestCase
{
    public function testAverage()
    {
        self::assertEquals(-3 / 2, Average::averageDatasetChange([6, 7, 6, 3, 0]));
    }

    public function testMean()
    {
        self::assertEquals(4, Average::arithmeticMean([1, 2, 3, 4, 5, 6, 7]), '', 0.01);

        self::assertEquals(69 / 20, Average::weightedAverage(
            [1, 2, 3, 4, 5, 6, 7],
            [0.1, 0.2, 0.3, 0.1, 0.2, 0.05, 0.05]
        ), '', 0.01);

        self::assertEquals(3.3800151591413, Average::geometricMean([1, 2, 3, 4, 5, 6, 7]), '', 0.01);
        self::assertEquals(2.6997245179063, Average::harmonicMean([1, 2, 3, 4, 5, 6, 7]), '', 0.01);

        self::assertEquals(-90, Average::angleMean([90.0, 180.0, 270.0, 360.0]), '', 0.01);
        self::assertEquals(9.999999999999977, Average::angleMean([370.0]), '', 0.01);

        self::assertEquals(270, Average::angleMean2([90.0, 180.0, 270.0, 360.0]), '', 0.01);
        self::assertEquals(9.999999999999977, Average::angleMean2([370.0]), '', 0.01);
    }

    /**
     * @expectedException phpOMS\Math\Matrix\Exception\InvalidDimensionException
     */
    public function testInvalidWeightedAverageDimension()
    {
        Average::weightedAverage([1, 2, 3, 4, 5, 6, 7], [0.1, 0.2, 0.3, 0.1, 0.2, 0.05]);
    }

    /**
     * @expectedException phpOMS\Math\Exception\ZeroDevisionException
     */
    public function testInvalidArithmeticMeanZeroDevision()
    {
        Average::arithmeticMean([]);
    }

    /**
     * @expectedException phpOMS\Math\Exception\ZeroDevisionException
     */
    public function testInvalidGeometricMean()
    {
        Average::geometricMean([]);
    }

    /**
     * @expectedException phpOMS\Math\Exception\ZeroDevisionException
     */
    public function testInvalidHarmonicMean()
    {
        Average::harmonicMean([1, 2, 3, 0, 5, 6, 7]);
    }

    public function testMode()
    {
        self::assertEquals(2, Average::mode([1, 2, 2, 3, 4, 4, 2]), '', 0.01);
    }

    public function testMedia()
    {
        self::assertEquals(4, Average::median([1, 2, 3, 4, 5, 6, 7]), '', 0.01);
        self::assertEquals(3.5, Average::median([1, 2, 3, 4, 5, 6]), '', 0.01);
    }
}
