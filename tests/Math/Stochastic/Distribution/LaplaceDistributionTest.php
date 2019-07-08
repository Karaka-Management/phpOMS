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

use phpOMS\Math\Stochastic\Distribution\LaplaceDistribution;

/**
 * @internal
 */
class LaplaceDistributionTest extends \PHPUnit\Framework\TestCase
{
    public function testPdf() : void
    {
        $x = 2;
        $m = 1;
        $b = 0.7;

        self::assertEqualsWithDelta(0.17118, LaplaceDistribution::getPdf($x, $m, $b), 0.01);
    }

    public function testCdf() : void
    {
        $x = 2;
        $m = 1;
        $b = 0.7;

        self::assertEqualsWithDelta(0.88017, LaplaceDistribution::getCdf($x, $m, $b), 0.01);
    }

    public function testMode() : void
    {
        self::assertEquals(2, LaplaceDistribution::getMode(2));
    }

    public function testMean() : void
    {
        self::assertEquals(2, LaplaceDistribution::getMean(2));
    }

    public function testMedian() : void
    {
        self::assertEquals(2, LaplaceDistribution::getMedian(2));
    }

    public function testExKurtosis() : void
    {
        self::assertEquals(3, LaplaceDistribution::getExKurtosis());
    }

    public function testSkewness() : void
    {
        self::assertEquals(0, LaplaceDistribution::getSkewness());
    }

    public function testVariance() : void
    {
        $b = 3;

        self::assertEquals(2 * $b ** 2, LaplaceDistribution::getVariance($b));
    }

    public function testMgf() : void
    {
        $t = 2;
        $b = 0.4;
        $m = 2;

        self::assertEquals(\exp($m * $t) / (1 - $b ** 2 * $t ** 2), LaplaceDistribution::getMgf($t, $m, $b));
    }

    public function testMgfException() : void
    {
        self::expectException(\OutOfBoundsException::class);

        LaplaceDistribution::getMgf(3, 2, 4);
    }
}
