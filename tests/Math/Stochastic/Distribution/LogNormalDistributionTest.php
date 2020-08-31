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

use phpOMS\Math\Stochastic\Distribution\LogNormalDistribution;

/**
 * @internal
 */
class LogNormalDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testPdf() : void
    {
        self::assertEqualsWithDelta(0.060069054, LogNormalDistribution::getPdf(3, 2, 2), 0.001);
    }

    public function testMean() : void
    {
        self::assertEqualsWithDelta(\exp(13 / 2), LogNormalDistribution::getMean(2, 3), 0.001);
    }

    public function testVariance() : void
    {
        self::assertEqualsWithDelta((\exp(9) - 1) * \exp(13), LogNormalDistribution::getVariance(2, 3), 0.001);
    }

    public function testStandardDeviation() : void
    {
        self::assertEqualsWithDelta(\exp(13 / 2) * \sqrt(\exp(9) - 1), LogNormalDistribution::getStandardDeviation(2, 3), 0.001);
        self::assertEqualsWithDelta(\sqrt((\exp(9) - 1) * \exp(13)), LogNormalDistribution::getStandardDeviation(2, 3), 0.001);
    }

    public function testSkewness() : void
    {
        self::assertEqualsWithDelta(\sqrt(\exp(9) - 1) * (\exp(9) + 2), LogNormalDistribution::getSkewness(3), 0.001);
    }

    public function testExKurtosis() : void
    {
        self::assertEqualsWithDelta(\exp(16) + 2 * \exp(12) + 3 * \exp(8) - 6, LogNormalDistribution::getExKurtosis(2), 0.001);
    }
}
