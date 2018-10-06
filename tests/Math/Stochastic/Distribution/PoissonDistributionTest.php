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

use phpOMS\Math\Stochastic\Distribution\PoissonDistribution;

class PoissonDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testPmf()
    {
        $k = 4;
        $l = 3;

        self::assertEquals(0.16803, PoissonDistribution::getPmf(4, 3), '', 0.01);
    }

    public function testCdf()
    {
        $k = 4;
        $l = 3;

        self::assertEquals(0.81526, PoissonDistribution::getCdf(4, 3), '', 0.01);
    }

    public function testMode()
    {
        $l = 4.6;

        self::assertEquals(4, PoissonDistribution::getMode($l), '', 0.01);
    }

    public function testMean()
    {
        $l = 4.6;

        self::assertEquals($l, PoissonDistribution::getMean($l));
    }

    public function testVariance()
    {
        $l = 4.6;

        self::assertEquals($l, PoissonDistribution::getVariance($l));
    }

    public function testSkewness()
    {
        $l = 4.6;

        self::assertEquals(1 / sqrt($l), PoissonDistribution::getSkewness($l));
    }

    public function testExKurtosis()
    {
        $l = 4.6;

        self::assertEquals(1 / $l, PoissonDistribution::getExKurtosis($l));
    }

    public function testMedian()
    {
        $l = 4.6;

        self::assertEquals(\floor($l + 1 / 3 - 0.02 / $l), PoissonDistribution::getMedian($l));
    }

    public function testFisherInformation()
    {
        $l = 4.6;

        self::assertEquals(1 / $l, PoissonDistribution::getFisherInformation($l));
    }

    public function testMgf()
    {
        $l = 4.6;
        $t = 3;

        self::assertEquals(exp($l * (\exp($t) - 1)), PoissonDistribution::getMgf($l, $t));
    }
}
