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

use phpOMS\Math\Stochastic\Distribution\LogisticDistribution;

/**
 * @internal
 */
class LogisticDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testPdf() : void
    {
        $x  = 3;
        $mu = 5;
        $s  = 2;

        self::assertEquals(
            \exp(-($x - $mu) / $s) / ($s * (1 + \exp(-($x - $mu) / $s)) ** 2),
            LogisticDistribution::getPdf($x, $mu, $s)
        );
    }

    public function testCdf() : void
    {
        $x  = 3;
        $mu = 5;
        $s  = 2;

        self::assertEquals(
            1 / (1 + \exp(-($x - $mu) / $s)),
            LogisticDistribution::getCdf($x, $mu, $s)
        );
    }

    public function testMode() : void
    {
        self::assertEquals(3, LogisticDistribution::getMode(3));
    }

    public function testMean() : void
    {
        self::assertEquals(3, LogisticDistribution::getMean(3));
    }

    public function testMedian() : void
    {
        self::assertEquals(3, LogisticDistribution::getMedian(3));
    }

    public function testVariance() : void
    {
        $s = 3;
        self::assertEquals(
            $s ** 2 * \M_PI ** 2 / 3,
            LogisticDistribution::getVariance($s)
        );
    }

    public function testStandardDeviation() : void
    {
        $s = 3;
        self::assertEquals(
            \sqrt($s ** 2 * \M_PI ** 2 / 3),
            LogisticDistribution::getStandardDeviation($s)
        );
    }

    public function testSkewness() : void
    {
        self::assertEquals(0, LogisticDistribution::getSkewness());
    }

    public function testExKurtosis() : void
    {
        self::assertEquals(6 / 5, LogisticDistribution::getExKurtosis());
    }

    public function testEntropy() : void
    {
        $s = 3;
        self::assertEquals(\log($s) + 2, LogisticDistribution::getEntropy($s));
    }
}
