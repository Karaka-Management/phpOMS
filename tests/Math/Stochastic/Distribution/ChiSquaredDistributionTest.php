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

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Stochastic\Distribution\ChiSquaredDistribution;

class ChiSquaredDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testHypothesisFalse()
    {
        $p = [0.6, 0.25, 0.15];
        $a = 0.05;

        $total    = 470;
        $observed = [255, 125, 90];
        $expected = [$total * $p[0], $total * $p[1], $total * $p[2]];

        $test = ChiSquaredDistribution::testHypothesis($observed, $expected, $a);

        self::assertEquals(8.46, $test['Chi2'], '', 0.1);
        self::assertNotEquals(0, $test['P']);
        self::assertFalse($test['H0']);
        self::assertEquals(2, $test['df']);
    }

    public function testDegreesOfFreedom()
    {
        self::assertEquals(2, ChiSquaredDistribution::getDegreesOfFreedom([1, 2, 3]));
        self::assertEquals(6, ChiSquaredDistribution::getDegreesOfFreedom([
            [1, 2, 3, 4],
            [1, 2, 3, 4],
            [1, 2, 3, 4],
        ]));
    }

    public function testMode()
    {
        self::assertEquals(max(5 - 2, 0), ChiSquaredDistribution::getMode(5));
    }

    public function testMean()
    {
        $df = 5;

        self::assertEquals($df, ChiSquaredDistribution::getMean($df));
    }

    public function testVariance()
    {
        $df = 5;

        self::assertEquals(2 * $df, ChiSquaredDistribution::getVariance($df));
    }

    public function testMedian()
    {
        $df = 5;

        self::assertEquals($df * (1 - 2 / (9 * $df)) ** 3, ChiSquaredDistribution::getMedian($df));
    }

    public function testSkewness()
    {
        $df = 5;

        self::assertEquals(sqrt(8 / $df), ChiSquaredDistribution::getSkewness($df));
    }

    public function testExKurtosis()
    {
        $df = 5;

        self::assertEquals(12 / $df, ChiSquaredDistribution::getExKurtosis($df));
    }

    public function testMgdf()
    {
        $df = 5;
        $t  = 0.3;

        self::assertEquals((1 - 2 * $t) ** (-$df / 2), ChiSquaredDistribution::getMgf($df, $t));
    }

    /**
     * @expectedException \Exception
     */
    public function testHypothesisSizeException()
    {
        ChiSquaredDistribution::testHypothesis([1, 2], [2]);
    }

    /**
     * @expectedException \Exception
     */
    public function testHypothesisDegreesOfFreedomException()
    {
        ChiSquaredDistribution::testHypothesis([], []);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testPdfOutOfBoundsException()
    {
        ChiSquaredDistribution::getPdf(-1, 0);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testMgfOutOfBoundsException()
    {
        ChiSquaredDistribution::getMgf(1, 0.6);
    }
}
