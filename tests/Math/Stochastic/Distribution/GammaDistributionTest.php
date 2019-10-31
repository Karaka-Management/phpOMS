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

use phpOMS\Math\Stochastic\Distribution\GammaDistribution;

/**
 * @internal
 */
class GammaDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testPDFIntegerK() : void
    {
        self::assertEqualsWithDelta(\exp(-1), GammaDistribution::getPdfIntegerK(1, 1, 1), 0.001);
        self::assertEqualsWithDelta(3 * \exp(-3/4) / 16, GammaDistribution::getPdfIntegerK(3, 2, 4), 0.001);
    }

    public function testMeanK() : void
    {
        self::assertEqualsWithDelta(8, GammaDistribution::getMeanK(2, 4), 0.001);
    }

    public function testVarianceK() : void
    {
        self::assertEqualsWithDelta(32, GammaDistribution::getVarianceK(2, 4), 0.001);
    }

    public function testStandardDeviationK() : void
    {
        self::assertEqualsWithDelta(\sqrt(32), GammaDistribution::getStandardDeviationK(2, 4), 0.001);
    }

    public function testExKurtosis() : void
    {
        self::assertEqualsWithDelta(3, GammaDistribution::getExKurtosis(2, 4), 0.001);
    }

    public function testSkewness() : void
    {
        self::assertEqualsWithDelta(\sqrt(2), GammaDistribution::getSkewness(2, 4), 0.001);
    }
}
