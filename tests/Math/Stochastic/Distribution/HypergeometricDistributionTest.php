<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Stochastic\Distribution\HypergeometricDistribution;

/**
 * @internal
 */
class HypergeometricDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testMean() : void
    {
        self::assertEquals(9, HypergeometricDistribution::getMean(15, 20, 12));
    }

    public function testVariance() : void
    {
        self::assertEqualsWithDelta(0.973328526784575 ** 2, HypergeometricDistribution::getVariance(15, 20, 12), 0.001);
    }

    public function testStandardDeviation() : void
    {
        self::assertEqualsWithDelta(0.973328526784575, HypergeometricDistribution::getStandardDeviation(15, 20, 12), 0.001);
    }

    public function testSkewness() : void
    {
        self::assertEqualsWithDelta(0.114156, HypergeometricDistribution::getSkewness(15, 20, 12), 0.001);
    }

    public function testExKurtosis() : void
    {
        self::assertEqualsWithDelta(-0.247277, HypergeometricDistribution::getExKurtosis(15, 20, 12), 0.001);
    }
}
