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

use phpOMS\Math\Stochastic\Distribution\LaplaceDistribution;

class LaplaceDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testPdf()
    {
        $x = 2;
        $m = 1;
        $b = 0.7;

        self::assertEquals(0.17118, LaplaceDistribution::getPdf($x, $m, $b), '', 0.01);
    }

    public function testCdf()
    {
        $x = 2;
        $m = 1;
        $b = 0.7;

        self::assertEquals(0.88017, LaplaceDistribution::getCdf($x, $m, $b), '', 0.01);
    }

    public function testMode()
    {
        self::assertEquals(2, LaplaceDistribution::getMode(2));
    }

    public function testMean()
    {
        self::assertEquals(2, LaplaceDistribution::getMean(2));
    }

    public function testMedian()
    {
        self::assertEquals(2, LaplaceDistribution::getMedian(2));
    }

    public function testExKurtosis()
    {
        self::assertEquals(3, LaplaceDistribution::getExKurtosis());
    }

    public function testSkewness()
    {
        self::assertEquals(0, LaplaceDistribution::getSkewness());
    }

    public function testVariance()
    {
        $b = 3;

        self::assertEquals(2 * $b ** 2, LaplaceDistribution::getVariance($b));
    }

    public function testMgf()
    {
        $t = 2;
        $b = 0.4;
        $m = 2;

        self::assertEquals(\exp($m * $t) / (1 - $b ** 2 * $t ** 2), LaplaceDistribution::getMgf($t, $m, $b));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testMgfException()
    {
        LaplaceDistribution::getMgf(3, 2, 4);
    }
}
