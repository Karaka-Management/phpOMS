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

use phpOMS\Math\Stochastic\Distribution\BinomialDistribution;

class BinomialDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testPmf() : void
    {
        $p = 0.4;
        $n = 20;
        $k = 7;

        self::assertEquals(0.1659, BinomialDistribution::getPmf($n, $k, $p), '', 0.01);
    }

    public function testCdf() : void
    {
        $p = 0.4;
        $n = 20;
        $k = 7;

        self::assertEquals(0.25, BinomialDistribution::getCdf($n, $k, $p), '', 0.01);
    }

    public function testMean() : void
    {
        $n = 20;
        $p = 0.4;

        self::assertEquals($n * $p, BinomialDistribution::getMean($n, $p), '', 0.01);
    }

    public function testMedian() : void
    {
        $n = 20;
        $p = 0.4;

        self::assertEquals(\floor($n * $p), BinomialDistribution::getMedian($n, $p), '', 0.01);
    }

    public function testMode() : void
    {
        $n = 20;
        $p = 0.4;

        self::assertEquals(\floor(($n + 1) * $p), BinomialDistribution::getMode($n, $p), '', 0.01);
    }

    public function testVariance() : void
    {
        $n = 20;
        $p = 0.4;

        self::assertEquals($n * $p * (1 - $p), BinomialDistribution::getVariance($n, $p), '', 0.01);
    }

    public function testSkewness() : void
    {
        $n = 20;
        $p = 0.4;

        self::assertEquals((1 - 2 * $p) / \sqrt($n * $p * (1 - $p)), BinomialDistribution::getSkewness($n, $p), '', 0.01);
    }

    public function testExKurtosis() : void
    {
        $n = 20;
        $p = 0.4;

        self::assertEquals((1 - 6 * $p * (1 - $p)) / ($n * $p * (1 - $p)), BinomialDistribution::getExKurtosis($n, $p), '', 0.01);
    }

    public function testMgf() : void
    {
        $n = 20;
        $p = 0.4;
        $t = 3;

        self::assertEquals((1 - $p + $p * \exp($t)) ** $n, BinomialDistribution::getMgf($n, $t, $p), '', 0.01);
    }

    public function testFisherInformation() : void
    {
        $n = 20;
        $p = 0.4;

        self::assertEquals($n / ($p * (1 - $p)), BinomialDistribution::getFisherInformation($n, $p), '', 0.01);
    }
}
