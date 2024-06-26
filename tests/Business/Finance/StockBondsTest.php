<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Business\Finance;

use phpOMS\Business\Finance\StockBonds;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Business\Finance\StockBondsTest: Stock & bond related  formulas')]
final class StockBondsTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The calculation of various stock/bond related ratios/yields is correct')]
    public function testRatios() : void
    {
        self::assertEquals(100 / 50, StockBonds::getBookValuePerShare(100, 50));
        self::assertEquals(100 / 50, StockBonds::getCurrentYield(100, 50));
        self::assertEquals(100 / 50, StockBonds::getDividendPayoutRatio(100, 50));
        self::assertEquals(100 / 50, StockBonds::getDividendYield(100, 50));
        self::assertEquals(100 / 50, StockBonds::getDividendsPerShare(100, 50));
        self::assertEquals(100 / 50, StockBonds::getEarningsPerShare(100, 50));
        self::assertEquals(100 / 50, StockBonds::getEquityMultiplier(100, 50));
        self::assertEquals(100 / 50, StockBonds::getPriceToBookValue(100, 50));
        self::assertEquals(100 / 50, StockBonds::getPriceEarningsRatio(100, 50));
        self::assertEquals(100 / 50, StockBonds::getPriceToSalesRatio(100, 50));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The calculation of the bond yield based on face value and price is correct')]
    public function testBondEquivalentYield() : void
    {
        self::assertEqualsWithDelta(0.40556, StockBonds::getBondEquivalentYield(100, 90, 100), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The calculation of the return of the capital asset pricing model is correct')]
    public function testExpectedReturnCAPM() : void
    {
        self::assertEqualsWithDelta(7, StockBonds::getExpectedReturnCAPM(3, 2, 5), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The capital gains yield calculation is correct')]
    public function testCapitalGainsYield() : void
    {
        self::assertEqualsWithDelta(0.1, StockBonds::getCapitalGainsYield(100, 110), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The diluted earnings per share calculation is correct')]
    public function testDilutedEarningsPerShare() : void
    {
        self::assertEqualsWithDelta(9.09, StockBonds::getDilutedEarningsPerShare(1000, 100, 10), 0.1);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The calculation of the absolute return for multiple holding periods is correct')]
    public function testHoldingPeriodReturn() : void
    {
        $r = [0.01, 0.02, 0.03, 0.04];

        self::assertEqualsWithDelta(0.10355, StockBonds::getHoldingPeriodReturn($r), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The tax equivalent yield is calculated correctly')]
    public function testTaxEquivalentYield() : void
    {
        $free = 0.15;
        $rate = 0.05;

        self::assertEqualsWithDelta(0.15789, StockBonds::getTaxEquivalentYield($free, $rate), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The net asset value is calculated correctly')]
    public function testNetAssetValue() : void
    {
        $assets      = 1000;
        $liabilities = 300;
        $shares      = 400;

        self::assertEqualsWithDelta(1.75, StockBonds::getNetAssetValue($assets, $liabilities, $shares), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The calculation of the present value of a stock with constant growth rate is correct')]
    public function testPresentValueOfStockConstantGrowth() : void
    {
        $div = 500;
        $r   = 0.15;
        $g   = 0.05;

        self::assertEqualsWithDelta(5000, StockBonds::getPresentValueOfStockConstantGrowth($div, $r, $g), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The total stock return including dividends and sales price is correct')]
    public function testTotalStockReturn() : void
    {
        $p0 = 1000;
        $p1 = 1200;
        $d  = 100;

        self::assertEqualsWithDelta(0.3, StockBonds::getTotalStockReturn($p0, $p1, $d), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The calculation of the yield of a bond is correct')]
    public function testYieldToMaturity() : void
    {
        $c = 100;
        $f = 1000;
        $p = 920;
        $n = 10;

        self::assertEqualsWithDelta(0.1138, StockBonds::getYieldToMaturity($c, $f, $p, $n), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The calculation of value of the zero coupon bond is correct')]
    public function testZeroCouponBondValue() : void
    {
        $f = 100;
        $r = 0.06;
        $t = 5;

        self::assertEqualsWithDelta(74.73, StockBonds::getZeroCouponBondValue($f, $r, $t), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The calculation of the yield of a zero coupon bond is correct')]
    public function testZeroCouponBondEffectiveYield() : void
    {
        $f  = 100;
        $pv = 90;
        $n  = 5;

        self::assertEqualsWithDelta(0.01517, StockBonds::getZeroCouponBondEffectiveYield($f, $pv, $n), 0.01);
    }
}
