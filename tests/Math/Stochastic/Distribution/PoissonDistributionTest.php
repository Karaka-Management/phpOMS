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
    public function testPmf() : void
    {
        $k = 4;
        $l = 3;

        self::assertEqualsWithDelta(0.16803, PoissonDistribution::getPmf(4, 3), 0.01);
    }

    public function testCdf() : void
    {
        $k = 4;
        $l = 3;

        self::assertEqualsWithDelta(0.81526, PoissonDistribution::getCdf(4, 3), 0.01);
    }

    public function testMode() : void
    {
        $l = 4.6;

        self::assertEqualsWithDelta(4, PoissonDistribution::getMode($l), 0.01);
    }

    public function testMean() : void
    {
        $l = 4.6;

        self::assertEquals($l, PoissonDistribution::getMean($l));
    }

    public function testVariance() : void
    {
        $l = 4.6;

        self::assertEquals($l, PoissonDistribution::getVariance($l));
    }

    public function testSkewness() : void
    {
        $l = 4.6;

        self::assertEquals(1 / \sqrt($l), PoissonDistribution::getSkewness($l));
    }

    public function testExKurtosis() : void
    {
        $l = 4.6;

        self::assertEquals(1 / $l, PoissonDistribution::getExKurtosis($l));
    }

    public function testMedian() : void
    {
        $l = 4.6;

        self::assertEquals(\floor($l + 1 / 3 - 0.02 / $l), PoissonDistribution::getMedian($l));
    }

    public function testFisherInformation() : void
    {
        $l = 4.6;

        self::assertEquals(1 / $l, PoissonDistribution::getFisherInformation($l));
    }

    public function testMgf() : void
    {
        $l = 4.6;
        $t = 3;

        self::assertEquals(\exp($l * (\exp($t) - 1)), PoissonDistribution::getMgf($l, $t));
    }
}
