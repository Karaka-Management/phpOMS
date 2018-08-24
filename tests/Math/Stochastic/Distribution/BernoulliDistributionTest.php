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

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Stochastic\Distribution\BernoulliDistribution;

class BernoulliDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testPmf()
    {
        self::assertEquals(0.3, BernoulliDistribution::getPmf(0.7, 0), '', 0.01);
        self::assertEquals(0.7, BernoulliDistribution::getPmf(0.7, 1), '', 0.01);
    }

    public function testMode()
    {
        self::assertEquals(1, BernoulliDistribution::getMode(0.7), '', 0.01);
        self::assertEquals(0, BernoulliDistribution::getMode(0.5), '', 0.01);
        self::assertEquals(0, BernoulliDistribution::getMode(0.3), '', 0.01);
    }

    public function testMean()
    {
        self::assertEquals(0.4, BernoulliDistribution::getMean(0.4), '', 0.01);
    }

    public function testCdf()
    {
        self::assertEquals(0, BernoulliDistribution::getCdf(0.4, -2), '', 0.01);
        self::assertEquals(1, BernoulliDistribution::getCdf(0.4, 2), '', 0.01);
        self::assertEquals(0.3, BernoulliDistribution::getCdf(0.7, 0.4), '', 0.01);
    }

    public function testMedian()
    {
        self::assertEquals(0.5, BernoulliDistribution::getMedian(0.5), '', 0.01);
        self::assertEquals(1, BernoulliDistribution::getMedian(0.7), '', 0.01);
        self::assertEquals(0, BernoulliDistribution::getMedian(0.3), '', 0.01);
    }

    public function testVariance()
    {
        $p = 0.3;
        $q = 1 - $p;

        self::assertEquals($p * $q, BernoulliDistribution::getVariance($p), '', 0.01);
    }

    public function testSkewness()
    {
        $p = 0.3;
        $q = 1 - $p;

        self::assertEquals((1 - 2 * $p) / \sqrt($p * $q), BernoulliDistribution::getSkewness($p), '', 0.01);
    }

    public function testExKurtosis()
    {
        $p = 0.3;
        $q = 1 - $p;

        self::assertEquals((1 - 6 * $p * $q) / ($p * $q), BernoulliDistribution::getExKurtosis($p), '', 0.01);
    }

    public function testEntropy()
    {
        $p = 0.3;
        $q = 1 - $p;

        self::assertEquals(-$q * \log($q) - $p * \log($p), BernoulliDistribution::getEntropy($p), '', 0.01);
    }

    public function testMgf()
    {
        $p = 0.3;
        $q = 1 - $p;
        $t = 2;

        self::assertEquals($q + $p * \exp($t), BernoulliDistribution::getMgf($p, $t), '', 0.01);
    }

    public function testFisherInformation()
    {
        $p = 0.3;
        $q = 1 - $p;

        self::assertEquals(1 / ($p * $q), BernoulliDistribution::getFisherInformation($p), '', 0.01);
    }
}
