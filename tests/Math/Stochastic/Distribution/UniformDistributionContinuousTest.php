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

use phpOMS\Math\Stochastic\Distribution\UniformDistributionContinuous;

class UniformDistributionContinuousTest extends \PHPUnit\Framework\TestCase
{
    public function testPdf()
    {
        $a = 1;
        $b = 4;

        self::assertEquals(1 / ($b - $a), UniformDistributionContinuous::getPdf(3, $a, $b));
        self::assertEquals(0, UniformDistributionContinuous::getPdf(0, $a, $b));
        self::assertEquals(0, UniformDistributionContinuous::getPdf(5, $a, $b));
    }

    public function testCdf()
    {
        $a = 1;
        $b = 4;
        $x = 3;

        self::assertEquals(($x - $a) / ($b - $a), UniformDistributionContinuous::getCdf($x, $a, $b));
        self::assertEquals(0, UniformDistributionContinuous::getCdf(0, $a, $b));
        self::assertEquals(1, UniformDistributionContinuous::getCdf(5, $a, $b));
    }

    public function testMode()
    {
        $a = 1;
        $b = 4;

        self::assertThat(
            UniformDistributionContinuous::getMode($a, $b),
            self::logicalAnd(
                self::greaterThan($a),
                self::lessThan($b)
            )
        );
    }

    public function testMean()
    {
        $a = 1;
        $b = 4;

        self::assertEquals(1 / 2 * ($b + $a), UniformDistributionContinuous::getMean($a, $b));
    }

    public function testMedian()
    {
        $a = 1;
        $b = 4;

        self::assertEquals(1 / 2 * ($b + $a), UniformDistributionContinuous::getMedian($a, $b));
    }

    public function testVariance()
    {
        $a = 1;
        $b = 4;

        self::assertEquals(1 / 12 * ($b - $a) ** 2, UniformDistributionContinuous::getVariance($a, $b));
    }

    public function testSkewness()
    {
        self::assertEquals(0, UniformDistributionContinuous::getSkewness());
    }

    public function testExKurtosis()
    {
        self::assertEquals(-6 / 5, UniformDistributionContinuous::getExKurtosis());
    }
}
