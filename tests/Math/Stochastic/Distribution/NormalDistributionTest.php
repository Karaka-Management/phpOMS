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
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Stochastic\Distribution\NormalDistribution;

/**
 * @internal
 */
class NormalDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testPdf() : void
    {
        $mean = 2;
        $sig  = 1;
        $x    = 3;

        self::assertEqualsWithDelta(0.24197, NormalDistribution::getPdf($x, $mean, $sig), 0.01);
    }

    public function testCdf() : void
    {
        $mean = 2;
        $sig  = 1;
        $x    = 3;

        self::assertEqualsWithDelta(0.84134, NormalDistribution::getCdf($x, $mean, $sig), 0.01);
    }

    public function testMean() : void
    {
        $mu = 4;

        self::assertEquals($mu, NormalDistribution::getMean($mu));
    }

    public function testMedian() : void
    {
        $mu = 4;

        self::assertEquals($mu, NormalDistribution::getMedian($mu));
    }

    public function testMode() : void
    {
        $mu = 4;

        self::assertEquals($mu, NormalDistribution::getMode($mu));
    }

    public function testSkewness() : void
    {
        self::assertEquals(0, NormalDistribution::getSkewness());
    }

    public function testExKurtosis() : void
    {
        self::assertEquals(0, NormalDistribution::getExKurtosis());
    }

    public function testVariance() : void
    {
        $sig = 0.8;

        self::assertEquals($sig ** 2, NormalDistribution::getVariance($sig));
    }
}
