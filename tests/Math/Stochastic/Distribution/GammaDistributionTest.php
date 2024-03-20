<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Stochastic\Distribution\GammaDistribution;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Stochastic\Distribution\GammaDistribution::class)]
final class GammaDistributionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testPdfScale() : void
    {
        self::assertEqualsWithDelta(0.0734, GammaDistribution::getPdfScale(10, 4, 3), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testPdfAlphaBete() : void
    {
        self::assertEqualsWithDelta(0.180447, GammaDistribution::getPdfRate(2, 4, 1), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testCdfScale() : void
    {
        self::assertEqualsWithDelta(0.42701, GammaDistribution::getCdfScale(10, 4, 3), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testCdfAlphaBete() : void
    {
        self::assertEqualsWithDelta(0.142876, GammaDistribution::getCdfRate(2, 4, 1), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testPdfIntegerScale() : void
    {
        self::assertEqualsWithDelta(\exp(-1), GammaDistribution::getPdfIntegerScale(1, 1, 1), 0.001);
        self::assertEqualsWithDelta(3 * \exp(-3 / 4) / 16, GammaDistribution::getPdfIntegerScale(3, 2, 4), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testPdfIntegerRate() : void
    {
        self::assertEqualsWithDelta(0.180447, GammaDistribution::getPdfIntegerRate(2, 4, 1), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMeanScale() : void
    {
        self::assertEqualsWithDelta(8, GammaDistribution::getMeanScale(2, 4), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMeanRate() : void
    {
        $alpha = 4;
        $beta  = 2;
        self::assertEquals($alpha / $beta, GammaDistribution::getMeanRate($alpha, $beta));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testVarianceScale() : void
    {
        self::assertEqualsWithDelta(32, GammaDistribution::getVarianceScale(2, 4), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testVarianceRate() : void
    {
        $alpha = 4;
        $beta  = 2;
        self::assertEquals($alpha / \pow($beta, 2), GammaDistribution::getVarianceRate($alpha, $beta));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStandardDeviationScale() : void
    {
        self::assertEqualsWithDelta(\sqrt(32), GammaDistribution::getStandardDeviationScale(2, 4), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStandardDeviationRate() : void
    {
        $alpha = 4;
        $beta  = 2;
        self::assertEquals(\sqrt($alpha / \pow($beta, 2)), GammaDistribution::getStandardDeviationRate($alpha, $beta));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testExKurtosis() : void
    {
        self::assertEqualsWithDelta(3, GammaDistribution::getExKurtosis(2), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testSkewness() : void
    {
        self::assertEqualsWithDelta(\sqrt(2), GammaDistribution::getSkewness(2), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMgfScale() : void
    {
        $theta = 2;
        $t     = 1 / $theta * 0.4;
        $k     = 3;
        self::assertEquals(\pow(1 - $theta * $t, -$k), GammaDistribution::getMgfScale($k, $t, $theta));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMgfRate() : void
    {
        $alpha = 4;
        $beta  = 3;
        $t     = 2;
        self::assertEquals(\pow(1 - $t / $beta, -$alpha), GammaDistribution::getMgfRate($t, $alpha, $beta));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testModeScale() : void
    {
        self::assertEquals((3 - 1) * 2, GammaDistribution::getModeScale(3, 2));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testModeRate() : void
    {
        self::assertEquals((3 - 1) / 2, GammaDistribution::getModeRate(3, 2));
    }
}
