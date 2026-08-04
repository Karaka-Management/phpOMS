<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Business\Finance;

use phpOMS\Business\Finance\Depreciation;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Business\Finance\DepreciationTest: Depreciation calculations')]
final class DepreciationTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The straight line depreciation and reverse value calculations are correct')]
    public function testStraightLine() : void
    {
        $start    = 23280;
        $duration = 6;
        $t        = 2;

        self::assertEqualsWithDelta(3880, Depreciation::getStraightLineDepreciation($start, $duration), 5);
        self::assertEqualsWithDelta(23280 - 3880 * $t, Depreciation::getStraightLineResidualInT($start, $duration, $t), 5);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The arithmetic degressive depreciation and reverse value calculations are correct')]
    public function testArithmeticDegressiveDepreciation() : void
    {
        $start    = 150000;
        $residual = 18000;
        $duration = 5;
        $t        = 2;

        self::assertEqualsWithDelta(8800, Depreciation::getArithmeticDegressiveDepreciationFactor($start, $residual, $duration), 5);
        self::assertEqualsWithDelta(35200, Depreciation::getArithmeticDegressiveDepreciationInT($start, $residual,$duration, $t), 5);
        self::assertEqualsWithDelta(70800, Depreciation::getArithmeticDegressiveDepreciationResidualInT($start, $residual, $duration, $t), 5);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The arithmetic progressive depreciation and reverse value calculations are correct')]
    public function testArithmeticProgressiveDepreciation() : void
    {
        $start    = 40000;
        $residual = 4700;
        $duration = 4;
        $t        = 2;

        self::assertEqualsWithDelta(3530, Depreciation::getArithmeticProgressiveDepreciationFactor($start, $residual, $duration), 5);
        self::assertEqualsWithDelta(7060, Depreciation::getArithmeticProgressiveDepreciationInT($start, $residual, $duration, $t), 5);
        self::assertEqualsWithDelta(29410, Depreciation::getArithmeticProgressiveDepreciationResidualInT($start, $residual, $duration, $t), 5);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The geometric progressive depreciation and reverse value calculations are correct')]
    public function testGeometricProgressiveDepreciation() : void
    {
        $start    = 150000;
        $residual = 18000;
        $duration = 5;
        $t        = 2;

        self::assertEqualsWithDelta(0.3456, Depreciation::getGeometicProgressiveDepreciationRate($start, $residual, $duration), 0.01);
        self::assertEqualsWithDelta(14527, Depreciation::getGeometicProgressiveDepreciationInT($start, $residual, $duration, $t), 5);
        self::assertEqualsWithDelta(125965, Depreciation::getGeometicProgressiveDepreciationResidualInT($start, $residual, $duration, $t), 5);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The geometric degressive depreciation and reverse value calculations are correct')]
    public function testGeometricDegressiveDepreciation() : void
    {
        $start    = 150000;
        $residual = 18000;
        $duration = 5;
        $t        = 2;

        self::assertEqualsWithDelta(0.3456, Depreciation::getGeometicDegressiveDepreciationRate($start, $residual, $duration), 0.01);
        self::assertEqualsWithDelta(33924, Depreciation::getGeometicDegressiveDepreciationInT($start, $residual, $duration, $t), 5);
        self::assertEqualsWithDelta(64236, Depreciation::getGeometicDegressiveDepreciationResidualInT($start, $residual, $duration, $t), 5);
    }
}
