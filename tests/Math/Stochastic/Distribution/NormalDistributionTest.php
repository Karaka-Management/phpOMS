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

use phpOMS\Math\Stochastic\Distribution\NormalDistribution;

class NormalDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testPdf()
    {
        $mean = 2;
        $sig  = 1;
        $x    = 3;

        self::assertEquals(0.24197, NormalDistribution::getPdf($x, $mean, $sig), '', 0.01);
    }

    public function testCdf()
    {
        $mean = 2;
        $sig  = 1;
        $x    = 3;

        self::assertEquals(0.84134, NormalDistribution::getCdf($x, $mean, $sig), '', 0.01);
    }

    public function testMean()
    {
        $mu = 4;

        self::assertEquals($mu, NormalDistribution::getMean($mu));
    }

    public function testMedian()
    {
        $mu = 4;

        self::assertEquals($mu, NormalDistribution::getMedian($mu));
    }

    public function testMode()
    {
        $mu = 4;

        self::assertEquals($mu, NormalDistribution::getMode($mu));
    }

    public function testSkewness()
    {
        self::assertEquals(0, NormalDistribution::getSkewness());
    }

    public function testExKurtosis()
    {
        self::assertEquals(0, NormalDistribution::getExKurtosis());
    }

    public function testVariance()
    {
        $sig = 0.8;

        self::assertEquals($sig ** 2, NormalDistribution::getVariance($sig));
    }
}
