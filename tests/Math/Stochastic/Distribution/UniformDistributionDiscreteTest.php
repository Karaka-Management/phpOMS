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

use phpOMS\Math\Stochastic\Distribution\UniformDistributionDiscrete;

class UniformDistributionDiscreteTest extends \PHPUnit\Framework\TestCase
{
    public function testPmf()
    {
        $a = 1;
        $b = 4;

        self::assertEquals(1 / ($b - $a + 1), UniformDistributionDiscrete::getPmf($a, $b));
    }

    public function testCdf()
    {
        $a = 1;
        $b = 4;
        $k = 3;

        self::assertEquals(($k - $a + 1) / ($b - $a + 1), UniformDistributionDiscrete::getCdf($k, $a, $b));
    }

    public function testSkewness()
    {
        self::assertEquals(0, UniformDistributionDiscrete::getSkewness());
    }

    public function testMean()
    {
        $a = 1;
        $b = 4;

        self::assertEquals(1 / 2 * ($a + $b), UniformDistributionDiscrete::getMean($a, $b));
    }

    public function testMedian()
    {
        $a = 1;
        $b = 4;

        self::assertEquals(1 / 2 * ($a + $b), UniformDistributionDiscrete::getMedian($a, $b));
    }

    public function testVariance()
    {
        $a = 1;
        $b = 4;

        self::assertEquals((($b - $a + 1) ** 2 - 1) / 12, UniformDistributionDiscrete::getVariance($a, $b));
    }

    public function testExKurtosis()
    {
        $a = 1;
        $b = 4;
        $n = $b - $a + 1;

        self::assertEquals(-(6 * ($n ** 2 + 1)) / (5 * ($n ** 2 - 1)), UniformDistributionDiscrete::getExKurtosis($a, $b));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testCdfExceptionUpper()
    {
        UniformDistributionDiscrete::getCdf(5, 2, 4);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testCdfExceptionLower()
    {
        UniformDistributionDiscrete::getCdf(1, 2, 4);
    }
}
