<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Business\Finance;

use phpOMS\Business\Finance\Depreciation;

/**
 * @testdox phpOMS\Business\Finance\DepreciationTest: Depreciation calculations
 *
 * @internal
 */
class DepreciationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The straight line deprecition and reverse value calculations are correct
     */
    public function testStraightLine() : void
    {
        $start    = 23280;
        $duration = 6;
        $t        = 2;

        self::assertEqualsWithDelta(3880, Depreciation::getStraightLineDepreciation($start, $duration), 5);
        self::assertEqualsWithDelta(23280 - 3880 * $t, Depreciation::getStraightLineResidualInT($start, $duration, $t), 5);
    }

    /**
     * @testdox The arithmetic degressiv deprecition and reverse value calculations are correct
     */
    public function testArithmeticDegressivDepreciation() : void
    {
        $start    = 150000;
        $residual = 18000;
        $duration = 5;
        $t        = 2;

        self::assertEqualsWithDelta(8800, Depreciation::getArithmeticDegressivDepreciationFactor($start, $residual, $duration), 5);
        self::assertEqualsWithDelta(35200, Depreciation::getArithmeticDegressivDepreciationInT($start, $residual,$duration, $t), 5);
        self::assertEqualsWithDelta(70800, Depreciation::getArithmeticDegressivDepreciationResidualInT($start, $residual, $duration, $t), 5);
    }

    /**
     * @testdox The arithmetic progressiv deprecition and reverse value calculations are correct
     */
    public function testArithmeticProgressivDepreciation() : void
    {
        $start    = 40000;
        $residual = 4700;
        $duration = 4;
        $t        = 2;

        self::assertEqualsWithDelta(3530, Depreciation::getArithmeticProgressivDepreciationFactor($start, $residual, $duration), 5);
        self::assertEqualsWithDelta(7060, Depreciation::getArithmeticProgressivDepreciationInT($start, $residual, $duration, $t), 5);
        self::assertEqualsWithDelta(29410, Depreciation::getArithmeticProgressivDepreciationResidualInT($start, $residual, $duration, $t), 5);
    }

    /**
     * @testdox The geometric progressiv deprecition and reverse value calculations are correct
     */
    public function testGeometricProgressivDepreciation() : void
    {
        $start    = 150000;
        $residual = 18000;
        $duration = 5;
        $t        = 2;

        self::assertEqualsWithDelta(0.3456, Depreciation::getGeometicProgressivDepreciationRate($start, $residual, $duration), 0.01);
        self::assertEqualsWithDelta(14527, Depreciation::getGeometicProgressivDepreciationInT($start, $residual, $duration, $t), 5);
        self::assertEqualsWithDelta(125965, Depreciation::getGeometicProgressivDepreciationResidualInT($start, $residual, $duration, $t), 5);
    }

    /**
     * @testdox The geometric degressiv deprecition and reverse value calculations are correct
     */
    public function testGeometricDegressivDepreciation() : void
    {
        $start    = 150000;
        $residual = 18000;
        $duration = 5;
        $t        = 2;

        self::assertEqualsWithDelta(0.3456, Depreciation::getGeometicDegressivDepreciationRate($start, $residual, $duration), 0.01);
        self::assertEqualsWithDelta(33924, Depreciation::getGeometicDegressivDepreciationInT($start, $residual, $duration, $t), 5);
        self::assertEqualsWithDelta(64236, Depreciation::getGeometicDegressivDepreciationResidualInT($start, $residual, $duration, $t), 5);
    }
}
