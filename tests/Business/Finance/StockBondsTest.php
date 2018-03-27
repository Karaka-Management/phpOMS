<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Business\Finance;

use phpOMS\Business\Finance\StockBonds;

class StockBondsTest extends \PHPUnit\Framework\TestCase
{
    public function testRatios()
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

    public function testBondEquivalentYield()
    {
        self::assertEquals(0.40556, StockBonds::getBondEquivalentYield(100, 90, 100), '', 0.01);
    }

    public function testExpectedReturnCAPM()
    {
        self::assertEquals(7, StockBonds::getExpectedReturnCAPM(3, 2, 5), '', 0.01);
    }

    public function testCapitalGainsYield()
    {
        self::assertEquals(0.1, StockBonds::getCapitalGainsYield(100, 110), '', 0.01);
    }

    public function testDilutedEarningsPerShare()
    {
        self::assertEquals(9.09, StockBonds::getDilutedEarningsPerShare(1000, 100, 10), '', 0.1);
    }

    public function testHoldingPeriodReturn()
    {
        $r = [0.01, 0.02, 0.03, 0.04];

        self::assertEquals(0.10355, StockBonds::getHoldingPeriodReturn($r), '', 0.01);
    }

    public function testTaxEquivalentYield()
    {
        $free = 0.15;
        $rate = 0.05;

        self::assertEquals(0.15789, StockBonds::getTaxEquivalentYield($free, $rate), '', 0.01);
    }

    public function testNetAssetValue()
    {
        $assets      = 1000;
        $liabilities = 300;
        $shares      = 400;

        self::assertEquals(1.75, StockBonds::getNetAssetValue($assets, $liabilities, $shares), '', 0.01);
    }

    public function testPresentValueOfStockConstantGrowth()
    {
        $div = 500;
        $r   = 0.15;
        $g   = 0.05;

        self::assertEquals(5000, StockBonds::getPresentValueOfStockConstantGrowth($div, $r, $g), '', 0.01);
    }

    public function testTotalStockReturn()
    {
        $p0 = 1000;
        $p1 = 1200;
        $d  = 100;

        self::assertEquals(0.3, StockBonds::getTotalStockReturn($p0, $p1, $d), '', 0.01);
    }

    public function testYieldToMaturity()
    {
        $c = 100;
        $f = 1000;
        $p = 920;
        $n = 10;

        self::assertEquals(0.1138, StockBonds::getYieldToMaturity($c, $f, $p, $n), '', 0.01);
    }

    public function testZeroCouponBondValue()
    {
        $f = 100;
        $r = 0.06;
        $t = 5;

        self::assertEquals(74.73, StockBonds::getZeroCouponBondValue($f, $r, $t), '', 0.01);
    }

    public function testZeroCouponBondEffectiveYield()
    {
        $f  = 100;
        $pv = 90;
        $n  = 5;

        self::assertEquals(0.01517, StockBonds::getZeroCouponBondEffectiveYield($f, $pv, $n), '', 0.01);
    }
}
