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

use phpOMS\Math\Stochastic\Distribution\BetaDistribution;

/**
 * @internal
 */
class BetaDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testMean() : void
    {
        self::assertEquals(1 / 2, BetaDistribution::getMean(2.0, 2.0));
    }

    public function testMode() : void
    {
        self::assertEquals(1 / 2, BetaDistribution::getMode(2.0, 2.0));
        self::assertEqualsWithDelta(0.2, BetaDistribution::getMode(2.0, 5.0), 0.1);
        self::assertEquals(0.0, BetaDistribution::getMode(1.0, 2.0));
        self::assertEquals(1.0, BetaDistribution::getMode(1.0, 1.0));
    }

    public function testVariance() : void
    {
        self::assertEqualsWithDelta(1 / 20, BetaDistribution::getVariance(2.0, 2.0), 0.001);
    }

    public function testStandardDeviation() : void
    {
        self::assertEqualsWithDelta(\sqrt(1 / 20), BetaDistribution::getStandardDeviation(2.0, 2.0), 0.001);
    }

    public function testSkewness() : void
    {
        self::assertEqualsWithDelta(0, BetaDistribution::getSkewness(2.0, 2.0), 0.001);
        self::assertEqualsWithDelta(0.565685, BetaDistribution::getSkewness(1.0, 2.0), 0.001);
        self::assertEqualsWithDelta(-0.565685, BetaDistribution::getSkewness(2.0, 1.0), 0.001);
    }

    public function testExKurtosis() : void
    {
        self::assertEqualsWithDelta(-6 / 7, BetaDistribution::getExKurtosis(2.0, 2.0), 0.001);
    }
}
