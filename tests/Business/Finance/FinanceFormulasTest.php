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

use phpOMS\Business\Finance\FinanceFormulas;

class FinanceFormulasTest extends \PHPUnit\Framework\TestCase
{
    public function testAnnualPercentageYield()
    {
        $expected = 0.06168;

        $r   = 0.06;
        $n   = 12;
        $apy = FinanceFormulas::getAnnualPercentageYield($r, $n);

        self::assertEquals(round($expected, 5), round($apy, 5));
        self::assertEquals(round($r, 2), FinanceFormulas::getStateAnnualInterestRateOfAPY($apy, $n));
    }

    public function testFutureValueOfAnnuity()
    {
        $expected = 5204.04;

        $P   = 1000.00;
        $r   = 0.02;
        $n   = 5;
        $fva = FinanceFormulas::getFutureValueOfAnnuity($P, $r, $n);

        self::assertEquals(round($expected, 2), round($fva, 2));
        self::assertEquals($n, FinanceFormulas::getNumberOfPeriodsOfFVA($fva, $P, $r));
        self::assertEquals(round($P, 2), round(FinanceFormulas::getPeriodicPaymentOfFVA($fva, $r, $n), 2));
    }

    public function testFutureValueOfAnnuityContinuousCompounding()
    {
        $expected = 12336.42;

        $cf    = 1000.00;
        $r     = 0.005;
        $t     = 12;
        $fvacc = FinanceFormulas::getFutureValueOfAnnuityConinuousCompounding($cf, $r, $t);

        self::assertEquals(round($expected, 2), round($fvacc, 2));
        self::assertEquals(round($cf, 2), round(FinanceFormulas::getCashFlowOfFVACC($fvacc, $r, $t), 2));
        self::assertEquals($t, FinanceFormulas::getTimeOfFVACC($fvacc, $cf, $r));
    }

    public function testAnnuityPaymentPV()
    {
        $expected = 212.16;

        $pv = 1000.00;
        $r  = 0.02;
        $n  = 5;
        $p  = FinanceFormulas::getAnnuityPaymentPV($pv, $r, $n);

        self::assertEquals(round($expected, 2), round($p, 2));
        self::assertEquals($n, FinanceFormulas::getNumberOfAPPV($p, $pv, $r));
        self::assertEquals(round($pv, 2), round(FinanceFormulas::getPresentValueOfAPPV($p, $r, $n), 2));
    }

    public function testAnnuityPaymentFV()
    {
        $expected = 192.16;

        $fv = 1000.00;
        $r  = 0.02;
        $n  = 5;
        $p  = FinanceFormulas::getAnnuityPaymentFV($fv, $r, $n);

        self::assertEquals(round($expected, 2), round($p, 2));
        self::assertEquals($n, FinanceFormulas::getNumberOfAPFV($p, $fv, $r));
        self::assertEquals(round($fv, 2), round(FinanceFormulas::getFutureValueOfAPFV($p, $r, $n), 2));
    }

    public function testAnnutiyPaymentFactorPV()
    {
        $expected = 0.21216;

        $r = 0.02;
        $n = 5;
        $p = FinanceFormulas::getAnnutiyPaymentFactorPV($r, $n);

        self::assertEquals(round($expected, 5), round($p, 5));
        self::assertEquals($n, FinanceFormulas::getNumberOfAPFPV($p, $r));
    }

    public function testPresentValueOfAnnuity()
    {
        $expected = 4713.46;

        $P   = 1000.00;
        $r   = 0.02;
        $n   = 5;
        $pva = FinanceFormulas::getPresentValueOfAnnuity($P, $r, $n);

        self::assertEquals(round($expected, 2), round($pva, 2));
        self::assertEquals($n, FinanceFormulas::getNumberOfPeriodsOfPVA($pva, $P, $r));
        self::assertEquals(round($P, 2), round(FinanceFormulas::getPeriodicPaymentOfPVA($pva, $r, $n), 2));
    }

    public function testPresentValueAnnuityFactor()
    {
        $expected = 4.7135;

        $r = 0.02;
        $n = 5;
        $p = FinanceFormulas::getPresentValueAnnuityFactor($r, $n);

        self::assertEquals(round($expected, 4), round($p, 4));
        self::assertEquals($n, FinanceFormulas::getPeriodsOfPVAF($p, $r));
    }

    public function testPresentValueOfAnnuityDue()
    {
        $expected = 454.60;

        $P = 100.00;
        $r = 0.05;
        $n = 5;

        $PV = FinanceFormulas::getPresentValueOfAnnuityDue($P, $r, $n);

        self::assertEquals(round($expected, 2), round($PV, 2));
        self::assertEquals(round($P, 2), FinanceFormulas::getPeriodicPaymentOfPVAD($PV, $r, $n));
        self::assertEquals($n, FinanceFormulas::getPeriodsOfPVAD($PV, $P, $r));
    }

    public function testFutureValueOfAnnuityDue()
    {
        $expected = 580.19;

        $P = 100.00;
        $r = 0.05;
        $n = 5;

        $FV = FinanceFormulas::getFutureValueOfAnnuityDue($P, $r, $n);

        self::assertEquals(round($expected, 2), round($FV, 2));
        self::assertEquals(round($P, 2), FinanceFormulas::getPeriodicPaymentOfFVAD($FV, $r, $n));
        self::assertEquals($n, FinanceFormulas::getPeriodsOfFVAD($FV, $P, $r));
    }

    public function testRatios()
    {
        self::assertEquals(300 / 400, FinanceFormulas::getRelativeMarketShareByShare(300, 400));
        self::assertEquals(300 / 400, FinanceFormulas::getRelativeMarketShareBySales(300, 400));

        self::assertEquals(3 / 2, FinanceFormulas::getAssetToSalesRatio(3, 2));
        self::assertEquals(2 / 3, FinanceFormulas::getAssetTurnoverRatio(3, 2));

        self::assertEquals(365 / 1000, FinanceFormulas::getDaysInInventory(1000));
        self::assertEquals(365 / 1000, FinanceFormulas::getAverageCollectionPeriod(1000));
        self::assertEquals(500 / 1000, FinanceFormulas::getReceivablesTurnover(500, 1000));
        self::assertEquals(500 / 1000, FinanceFormulas::getCurrentRatio(500, 1000));

        self::assertEquals(500 / 1000, FinanceFormulas::getDebtCoverageRatio(500, 1000));
        self::assertEquals(500 / 1000, FinanceFormulas::getDebtRatio(500, 1000));
        self::assertEquals(500 / 1000, FinanceFormulas::getDebtToEquityRatio(500, 1000));
        self::assertEquals(500 / 1000, FinanceFormulas::getDebtToIncomeRatio(500, 1000));

        self::assertEquals(500 / 1000, FinanceFormulas::getInterestCoverageRatio(500, 1000));
        self::assertEquals(500 / 1000, FinanceFormulas::getInventoryTurnoverRatio(500, 1000));
        self::assertEquals(500 / 1000, FinanceFormulas::getNetProfitMargin(500, 1000));

        self::assertEquals(500 / 1000, FinanceFormulas::getReturnOnAssets(500, 1000));
        self::assertEquals(500 / 1000, FinanceFormulas::getReturnOnEquity(500, 1000));
        self::assertEquals(500 / 1000, FinanceFormulas::getReceivablesTurnoverRatio(500, 1000));
        self::assertEquals(500 / 1000, FinanceFormulas::getQuickRatio(500, 1000));

        self::assertEquals(500 / 1000 - 1, FinanceFormulas::getReturnOnInvestment(500, 1000));
        self::assertEquals((500 - 300) / 500, FinanceFormulas::getRetentionRatio(500, 300));
        self::assertEquals(500 / 1000 - 1, FinanceFormulas::getRateOfOnflation(500, 1000));

        self::assertEquals(1000 / 500, FinanceFormulas::getPaybackPeriod(1000, 500));
        self::assertEquals(100 / 0.15, FinanceFormulas::getPresentValueOfPerpetuity(100, 0.15));
    }

    public function testCompound()
    {
        $expected = 15.76;

        $P = 100.00;
        $r = 0.05;
        $t = 3;

        $C = round(FinanceFormulas::getCompoundInterest($P, $r, $t), 2);

        self::assertEquals(round($expected, 2), $C);
        self::assertTrue(abs($P - FinanceFormulas::getPrincipalOfCompundInterest($C, $r, $t)) < 0.1);
        self::assertEquals($t, (int) round(FinanceFormulas::getPeriodsOfCompundInterest($P, $C, $r), 0));
    }

    public function testContinuousCompounding()
    {
        $expected = 116.18;

        $P = 100.00;
        $r = 0.05;
        $t = 3;

        $C = round(FinanceFormulas::getContinuousCompounding($P, $r, $t), 2);

        self::assertEquals(round($expected, 2), $C);
        self::assertEquals(round($P, 2), round(FinanceFormulas::getPrincipalOfContinuousCompounding($C, $r, $t), 2));
        self::assertTrue(abs($t - FinanceFormulas::getPeriodsOfContinuousCompounding($P, $C, $r)) < 0.01);
        self::assertTrue(abs($r - FinanceFormulas::getRateOfContinuousCompounding($P, $C, $t)) < 0.01);
    }

    public function testSimpleInterest()
    {
        $P = 100.00;
        $r = 0.05;
        $t = 3;

        $I = $P * $r * $t;

        self::assertTrue(abs($I - FinanceFormulas::getSimpleInterest($P, $r, $t)) < 0.01);
        self::assertTrue(abs($P - FinanceFormulas::getSimpleInterestPrincipal($I, $r, $t)) < 0.01);
        self::assertTrue(abs($r - FinanceFormulas::getSimpleInterestRate($I, $P, $t)) < 0.01);
        self::assertEquals($t, FinanceFormulas::getSimpleInterestTime($I, $P, $r));
    }

    public function testDiscountedPaybackPeriod()
    {
        $O1 = 5000;
        $r  = 0.05;
        $CF = 1000;

        self::assertTrue(abs(5.896 - FinanceFormulas::getDiscountedPaybackPeriod($CF, $O1, $r)) < 0.01);
    }

    public function testDoublingTime()
    {
        $r = 0.05;

        self::assertTrue(abs(14.207 - FinanceFormulas::getDoublingTime($r)) < 0.01);
        self::assertTrue(abs($r - FinanceFormulas::getDoublingRate(14.207)) < 0.01);
    }

    public function testDoublingTimeContinuousCompounding()
    {
        $r = 0.05;

        self::assertEquals(13.863, FinanceFormulas::getDoublingTimeContinuousCompounding($r), '', 0.01);
    }

    public function testEquivalentAnnualAnnuity()
    {
        $npv = 1000;
        $r   = 0.15;
        $n   = 7;

        self::assertEquals(240.36, FinanceFormulas::getEquivalentAnnualAnnuity($npv, $r, $n), '', 0.01);
        self::assertEquals($n, FinanceFormulas::getPeriodsOfEAA(240.36, $npv, $r));
        self::assertEquals($npv, FinanceFormulas::getNetPresentValueOfEAA(240.36, $r, $n), '', 0.01);
    }

    public function testFreeCashFlowToEquity()
    {
        $income    = 1000;
        $depamo    = 300;
        $capital   = 400;
        $wc        = 200;
        $borrowing = 500;

        self::assertEquals(1200, FinanceFormulas::getFreeCashFlowToEquity($income, $depamo, $capital, $wc, $borrowing), '', 0.01);
    }

    public function testFreeCashFlowToFirm()
    {
        $ebit    = 1000;
        $depamo  = 300;
        $t       = 0.15;
        $capital = 400;
        $wc      = 200;

        self::assertEquals(550, FinanceFormulas::getFreeCashFlowToFirm($ebit, $t, $depamo, $capital, $wc), '', 0.01);
    }

    public function testFutureValue()
    {
        $c = 1000;
        $r = 0.15;
        $n = 7;

        self::assertEquals(2660.02, FinanceFormulas::getFutureValue($c, $r, $n), '', 0.01);
    }

    public function testFutureValueContinuousCompounding()
    {
        $pv = 1000;
        $r  = 0.15;
        $t  = 7;

        self::assertEquals(2857.65, FinanceFormulas::getFutureValueContinuousCompounding($pv, $r, $t), '', 0.01);
    }

    public function testValueFactor()
    {
        $r = 0.15;
        $n = 7;

        self::assertEquals(2.66, FinanceFormulas::getFutureValueFactor($r, $n), '', 0.01);
        self::assertEquals(0.37594, FinanceFormulas::getPresentValueFactor($r, $n), '', 0.01);
    }

    public function testGeometricMeanReturn()
    {
        $r = [0.01, 0.02, 0.03, 0.04, 0.05, 0.06, 0.07];

        self::assertEquals(0.04123, FinanceFormulas::getGeometricMeanReturn($r), '', 0.01);
    }

    public function testGrowingAnnuityFV()
    {
        $p = 1000;
        $r = 0.15;
        $g = 0.1;
        $n = 7;

        self::assertEquals(14226.06, FinanceFormulas::getGrowingAnnuityFV($p, $r, $g, $n), '', 0.01);
    }

    public function testGrowingAnnuityPaymentPV()
    {
        $p = 1000;
        $r = 0.15;
        $g = 0.1;
        $n = 7;

        self::assertEquals(186.98, FinanceFormulas::getGrowingAnnuityPaymentPV($p, $r, $g, $n), '', 0.01);
    }

    public function testGrowingAnnuityPaymentFV()
    {
        $fv = 1000;
        $r  = 0.15;
        $g  = 0.1;
        $n  = 7;

        self::assertEquals(70.29, FinanceFormulas::getGrowingAnnuityPaymentFV($fv, $r, $g, $n), '', 0.01);
    }

    public function testGrowingAnnuityPV()
    {
        $p = 1000;
        $r = 0.15;
        $g = 0.1;
        $n = 7;

        self::assertEquals(5348.1, FinanceFormulas::getGrowingAnnuityPV($p, $r, $g, $n), '', 0.01);
    }

    public function testGrowingPerpetuityPV()
    {
        $d = 1000;
        $r = 0.15;
        $g = 0.1;

        self::assertEquals(20000, FinanceFormulas::getGrowingPerpetuityPV($d, $r, $g), '', 0.01);
    }

    public function testNetPresentValue()
    {
        $c = [1000, 100, 200, 300, 400, 500, 600];
        $r = 0.15;

        self::assertEquals(172.13, FinanceFormulas::getNetPresentValue($c, $r), '', 0.01);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testInvalidNetPresentValue()
    {
        FinanceFormulas::getNetPresentValue([], 0.1);
    }

    public function testRealRateOfReturn()
    {
        $nominal   = 0.15;
        $inflation = 0.05;

        self::assertEquals(0.09524, FinanceFormulas::getRealRateOfReturn($nominal, $inflation), '', 0.01);
    }

    public function testNetWorkingCapital()
    {
        self::assertEquals(1000 - 600, FinanceFormulas::getNetWorkingCapital(1000, 600), '', 0.01);
    }

    public function testNumberOfPeriodsPVFV()
    {
        $fv = 1200;
        $pv = 1000;
        $r  = 0.03;

        self::assertEquals(6.1681, FinanceFormulas::getNumberOfPeriodsPVFV($fv, $pv, $r), '', 0.01);
    }

    public function testPresentValue()
    {
        $c = 1000;
        $r = 0.15;
        $n = 7;

        self::assertEquals(375.94, FinanceFormulas::getPresentValue($c, $r, $n), '', 0.01);
    }

    public function testPresentValueContinuousCompounding()
    {
        $c = 1000;
        $r = 0.15;
        $t = 7;

        self::assertEquals(349.94, FinanceFormulas::getPresentValueContinuousCompounding($c, $r, $t), '', 0.01);
    }
}