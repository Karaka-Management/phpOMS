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

use phpOMS\Math\Stochastic\Distribution\WeibullDistribution;

/**
 * @internal
 */
class WeibullDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testPdf() : void
    {
        self::assertEqualsWithDelta(0.213668559, WeibullDistribution::getPdf(3, 4, 2), 0.001);
        self::assertEqualsWithDelta(0.0, WeibullDistribution::getPdf(-1, 4, 2), 0.001);
    }

    public function testCdf() : void
    {
        self::assertEqualsWithDelta(0.430217175, WeibullDistribution::getCdf(3, 4, 2), 0.001);
        self::assertEqualsWithDelta(0.0, WeibullDistribution::getCdf(-1, 4, 2), 0.001);
    }

    public function testMean() : void
    {
        self::assertEqualsWithDelta(3.54490771, WeibullDistribution::getMean(4, 2), 0.001);
    }

    public function testMedian() : void
    {
        self::assertEqualsWithDelta(3.33021844, WeibullDistribution::getMedian(4, 2), 0.001);
    }

    public function testMode() : void
    {
        self::assertEqualsWithDelta(2.82842712, WeibullDistribution::getMode(4, 2), 0.001);
    }

    public function testVariance() : void
    {
        self::assertEqualsWithDelta(3.43362932, WeibullDistribution::getVariance(4, 2), 0.001);
    }

    public function testStandardDeviation() : void
    {
        self::assertEqualsWithDelta(\sqrt(3.43362932), WeibullDistribution::getStandardDeviation(4, 2), 0.001);
    }

    public function testEntropy() : void
    {
        self::assertEqualsWithDelta(1.981755, WeibullDistribution::getEntropy(4, 2), 0.001);
    }

    public function testSkewness() : void
    {
        self::assertEqualsWithDelta(0.631110, WeibullDistribution::getSkewness(4, 2), 0.001);
    }
}
