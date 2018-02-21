<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
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

    public function testNetAssetValue()
    {
        $assets      = 1000;
        $liabilities = 300;
        $shares      = 400;

        self::assertEquals(1.75, StockBonds::getNetAssetValue($assets, $liabilities, $shares), '', 0.01);
    }
}
