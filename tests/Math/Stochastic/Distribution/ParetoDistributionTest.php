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

use phpOMS\Math\Stochastic\Distribution\ParetoDistribution;

/**
 * @internal
 */
class ParetoDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testPdf() : void
    {
        self::assertEqualsWithDelta(0.263374485596, ParetoDistribution::getPdf(3, 2, 4), 0.001);
    }

    public function testCdf() : void
    {
        self::assertEqualsWithDelta(0.8024691358, ParetoDistribution::getCdf(3, 2, 4), 0.001);
    }

    public function testMean() : void
    {
        self::assertEqualsWithDelta(8 / 3, ParetoDistribution::getMean(2, 4), 0.001);
        self::assertEquals(\PHP_FLOAT_MAX, ParetoDistribution::getMean(2, 1));
    }

    public function testVariance() : void
    {
        self::assertEqualsWithDelta(2, ParetoDistribution::getVariance(3, 4), 0.001);
        self::assertEqualsWithDelta(\PHP_FLOAT_MAX, ParetoDistribution::getVariance(3, 2), 0.001);
    }

    public function testStandardDeviation() : void
    {
        self::assertEqualsWithDelta(\sqrt(2), ParetoDistribution::getStandardDeviation(3, 4), 0.001);
    }

    public function testExKurtosis() : void
    {
        self::assertEqualsWithDelta(35.666666666666664, ParetoDistribution::getExKurtosis(6, 5), 0.001);
        self::assertEquals(0.0, ParetoDistribution::getExKurtosis(4));
    }

    public function testSkewness() : void
    {
        self::assertEqualsWithDelta(3.810317377662722, ParetoDistribution::getSkewness(6, 5), 0.001);
        self::assertEquals(0.0, ParetoDistribution::getSkewness(3));
    }
}
