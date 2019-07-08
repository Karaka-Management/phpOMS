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

use phpOMS\Math\Stochastic\Distribution\GeometricDistribution;

/**
 * @internal
 */
class GeometricDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testPmf() : void
    {
        $p = 0.2;
        $k = 4;

        self::assertEqualsWithDelta(0.1024, GeometricDistribution::getPmf($p, $k), 0.01);
    }

    public function testCdf() : void
    {
        $p = 0.2;
        $k = 6;

        // P(X > 6) = P(X <= 6) => 1 - CDF
        self::assertEqualsWithDelta(0.262, 1 - GeometricDistribution::getCdf($p, $k), 0.01);
    }

    public function testMode() : void
    {
        self::assertEquals(1, GeometricDistribution::getMode());
    }

    public function testMean() : void
    {
        $p = 0.3;
        self::assertEquals(1 / $p, GeometricDistribution::getMean($p));
    }

    public function testVariance() : void
    {
        $p = 0.3;

        self::assertEquals((1 - $p) / $p ** 2, GeometricDistribution::getVariance($p));
    }

    public function testSkewness() : void
    {
        $p = 0.3;

        self::assertEquals((2 - $p) / \sqrt(1 - $p), GeometricDistribution::getSkewness($p));
    }

    public function testExKurtosis() : void
    {
        $p = 0.3;

        self::assertEquals(6 + ($p ** 2) / (1 - $p), GeometricDistribution::getExKurtosis($p));
    }

    public function testMedian() : void
    {
        $p = 0.3;

        self::assertEquals(\ceil(-1 / \log(1 - $p, 2)), GeometricDistribution::getMedian($p));
    }
}
