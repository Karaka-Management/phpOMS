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

use phpOMS\Math\Stochastic\Distribution\ExponentialDistribution;

class ExponentialDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testPdf()
    {
        $lambda = 0.1;
        $x      = 7;

        self::assertEquals(0.049659, ExponentialDistribution::getPdf($x, $lambda), '', 0.01);
    }

    public function testCdf()
    {
        $lambda = 0.1;
        $x      = 7;

        self::assertEquals(0.5034, ExponentialDistribution::getCdf($x, $lambda), '', 0.01);
    }

    public function testMean()
    {
        self::assertEquals(1/3, ExponentialDistribution::getMean(3));
    }

    public function testMode()
    {
        self::assertEquals(0, ExponentialDistribution::getMode());
    }

    public function testMedian()
    {
        self::assertEquals(1/3 * log(2), ExponentialDistribution::getMedian(3));
    }

    public function testMgf()
    {
        $lambda = 3;
        $t      = 2;

        self::assertEquals($lambda / ($lambda - $t), ExponentialDistribution::getMgf($t, $lambda));
    }

    public function testVariance()
    {
        self::assertEquals(1/(3 ** 2), ExponentialDistribution::getVariance(3));
    }

    public function testExKurtosis()
    {
        self::assertEquals(6, ExponentialDistribution::getExKurtosis());
    }

    public function testSkewness()
    {
        self::assertEquals(2, ExponentialDistribution::getSkewness());
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testMgfException()
    {
        ExponentialDistribution::getMgf(3, 3);
    }
}
