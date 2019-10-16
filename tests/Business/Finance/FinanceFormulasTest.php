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

namespace phpOMS\tests\Business\Finance;

use phpOMS\Business\Finance\FinanceFormulas;

/**
 * @testdox phpOMS\Business\Finance\FinanceFormulasTest: Finance formulas
 *
 * @internal
 */
class FinanceFormulasTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The annual percentage yield (APY) and reverse value calculations are correct
     */
    public function testAnnualPercentageYield() : void
    {
        $expected = 0.06168;

        $r   = 0.06;
        $n   = 12;
        $apy = FinanceFormulas::getAnnualPercentageYield($r, $n);

        self::assertEquals(\round($expected, 5), \round($apy, 5));
        self::assertEquals(\round($r, 2), FinanceFormulas::getStateAnnualInterestRateOfAPY($apy, $n));
    }

    /**
     * @testdox The future value of annuity (FVA) and reverse value calculations are correct
     */
    public function testFutureValueOfAnnuity() : void
    {
        $expected = 5204.04;

        $P   = 1000.00;
        $r   = 0.02;
        $n   = 5;
        $fva = FinanceFormulas::getFutureValueOfAnnuity($P, $r, $n);

        self::assertEquals(\round($expected, 2), \round($fva, 2));
        self::assertEquals($n, FinanceFormulas::getNumberOfPeriodsOfFVA($fva, $P, $r));
        self::assertEquals(\round($P, 2), \round(FinanceFormulas::getPeriodicPaymentOfFVA($fva, $r, $n), 2));
    }

    /**
     * @testdox The future value of annuity continuous compounding (FVACC) and reverse value calculations are correct
     */
    public function testFutureValueOfAnnuityContinuousCompounding() : void
    {
        $expected = 12336.42;

        $cf    = 1000.00;
        $r     = 0.005;
        $t     = 12;
        $fvacc = FinanceFormulas::getFutureValueOfAnnuityConinuousCompounding($cf, $r, $t);

        self::assertEquals(\round($expected, 2), \round($fvacc, 2));
        self::assertEquals(\round($cf, 2), \round(FinanceFormulas::getCashFlowOfFVACC($fvacc, $r, $t), 2));
        self::assertEquals($t, FinanceFormulas::getTimeOfFVACC($fvacc, $cf, $r));
    }

    /**
     * @testdox The annuity payment from the present value (PV) and reverse value calculations are correct
     */
    public function testAnnuityPaymentPV() : void
    {
        $expected = 212.16;

        $pv = 1000.00;
        $r  = 0.02;
        $n  = 5;
        $p  = FinanceFormulas::getAnnuityPaymentPV($pv, $r, $n);

        self::assertEquals(\round($expected, 2), \round($p, 2));
        self::assertEquals($n, FinanceFormulas::getNumberOfAPPV($p, $pv, $r));
        self::assertEquals(\round($pv, 2), \round(FinanceFormulas::getPresentValueOfAPPV($p, $r, $n), 2));
    }

    /**
     * @testdox The annuity payment from the future value (FV) and reverse value calculations are correct
     */
    public function testAnnuityPaymentFV() : void
    {
        $expected = 192.16;

        $fv = 1000.00;
        $r  = 0.02;
        $n  = 5;
        $p  = FinanceFormulas::getAnnuityPaymentFV($fv, $r, $n);

        self::assertEquals(\round($expected, 2), \round($p, 2));
        self::assertEquals($n, FinanceFormulas::getNumberOfAPFV($p, $fv, $r));
        self::assertEquals(\round($fv, 2), \round(FinanceFormulas::getFutureValueOfAPFV($p, $r, $n), 2));
    }

    /**
     * @testdox The annuity payment from the present value (PV) and reverse value calculations are correct
     */
    public function testAnnutiyPaymentFactorPV() : void
    {
        $expected = 0.21216;

        $r = 0.02;
        $n = 5;
        $p = FinanceFormulas::getAnnutiyPaymentFactorPV($r, $n);

        self::assertEquals(\round($expected, 5), \round($p, 5));
        self::assertEquals($n, FinanceFormulas::getNumberOfAPFPV($p, $r));
    }

    /**
     * @testdox The present value of the annuity is correct
     */
    public function testPresentValueOfAnnuity() : void
    {
        $expected = 4713.46;

        $P   = 1000.00;
        $r   = 0.02;
        $n   = 5;
        $pva = FinanceFormulas::getPresentValueOfAnnuity($P, $r, $n);

        self::assertEquals(\round($expected, 2), \round($pva, 2));
        self::assertEquals($n, FinanceFormulas::getNumberOfPeriodsOfPVA($pva, $P, $r));
        self::assertEquals(\round($P, 2), \round(FinanceFormulas::getPeriodicPaymentOfPVA($pva, $r, $n), 2));
    }

    /**
     * @testdox The present value annuity factor of the annuity is correct
     */
    public function testPresentValueAnnuityFactor() : void
    {
        $expected = 4.7135;

        $r = 0.02;
        $n = 5;
        $p = FinanceFormulas::getPresentValueAnnuityFactor($r, $n);

        self::assertEquals(\round($expected, 4), \round($p, 4));
        self::assertEquals($n, FinanceFormulas::getPeriodsOfPVAF($p, $r));
    }

    /**
     * @testdox The due present value the annuity is correct
     */
    public function testPresentValueOfAnnuityDue() : void
    {
        $expected = 454.60;

        $P = 100.00;
        $r = 0.05;
        $n = 5;

        $PV = FinanceFormulas::getPresentValueOfAnnuityDue($P, $r, $n);

        self::assertEquals(\round($expected, 2), \round($PV, 2));
        self::assertEquals(\round($P, 2), FinanceFormulas::getPeriodicPaymentOfPVAD($PV, $r, $n));
        self::assertEquals($n, FinanceFormulas::getPeriodsOfPVAD($PV, $P, $r));
    }

    /**
     * @testdox The due future value the annuity is correct
     */
    public function testFutureValueOfAnnuityDue() : void
    {
        $expected = 580.19;

        $P = 100.00;
        $r = 0.05;
        $n = 5;

        $FV = FinanceFormulas::getFutureValueOfAnnuityDue($P, $r, $n);

        self::assertEquals(\round($expected, 2), \round($FV, 2));
        self::assertEquals(\round($P, 2), FinanceFormulas::getPeriodicPaymentOfFVAD($FV, $r, $n));
        self::assertEquals($n, FinanceFormulas::getPeriodsOfFVAD($FV, $P, $r));
    }

    /**
     * @testdox The relative market share calculations by shares and ales are correct
     */
    public function testRelativeMarketShare() : void
    {
        self::assertEquals(300 / 400, FinanceFormulas::getRelativeMarketShareByShare(300, 400));
        self::assertEquals(300 / 400, FinanceFormulas::getRelativeMarketShareBySales(300, 400));
    }

    /**
     * @testdox The asset ratio calculations are correct
     */
    public function testAssetRatios() : void
    {
        self::assertEquals(3 / 2, FinanceFormulas::getAssetToSalesRatio(3, 2));
        self::assertEquals(2 / 3, FinanceFormulas::getAssetTurnoverRatio(3, 2));
    }

    /**
     * @testdox Balance ratio calculations for DII, Receivables/Turnover, and more are correct
     */
    public function testBalanceRatios() : void
    {
        self::assertEquals(365 / 1000, FinanceFormulas::getDaysInInventory(1000));
        self::assertEquals(365 / 1000, FinanceFormulas::getAverageCollectionPeriod(1000));
        self::assertEquals(500 / 1000, FinanceFormulas::getReceivablesTurnover(500, 1000));
        self::assertEquals(500 / 1000, FinanceFormulas::getCurrentRatio(500, 1000));
    }

    /**
     * @testdox Dept ratios for dept coverage, dept to equity and dept to income are correct
     */
    public function testDeptRatios() : void
    {
        self::assertEquals(500 / 1000, FinanceFormulas::getDebtCoverageRatio(500, 1000));
        self::assertEquals(500 / 1000, FinanceFormulas::getDebtRatio(500, 1000));
        self::assertEquals(500 / 1000, FinanceFormulas::getDebtToEquityRatio(500, 1000));
        self::assertEquals(500 / 1000, FinanceFormulas::getDebtToIncomeRatio(500, 1000));
    }

    public function testRatios() : void
    {
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

    /**
     * @testdox Compound calculations for interest, principal and periods are correct
     */
    public function testCompound() : void
    {
        $expected = 15.76;

        $P = 100.00;
        $r = 0.05;
        $t = 3;

        $C = \round(FinanceFormulas::getCompoundInterest($P, $r, $t), 2);

        self::assertEquals(\round($expected, 2), $C);
        self::assertEqualsWithDelta($P, FinanceFormulas::getPrincipalOfCompundInterest($C, $r, $t), 0.1);
        self::assertEquals($t, (int) \round(FinanceFormulas::getPeriodsOfCompundInterest($P, $C, $r), 0));
    }

    /**
     * @testdox Continuous compound calculations for interest, principal and periods are correct
     */
    public function testContinuousCompounding() : void
    {
        $expected = 116.18;

        $P = 100.00;
        $r = 0.05;
        $t = 3;

        $C = \round(FinanceFormulas::getContinuousCompounding($P, $r, $t), 2);

        self::assertEquals(\round($expected, 2), $C);
        self::assertEquals(\round($P, 2), \round(FinanceFormulas::getPrincipalOfContinuousCompounding($C, $r, $t), 2));
        self::assertEqualsWithDelta($t, FinanceFormulas::getPeriodsOfContinuousCompounding($P, $C, $r), 0.01);
        self::assertEqualsWithDelta($r, FinanceFormulas::getRateOfContinuousCompounding($P, $C, $t), 0.01);
    }

    /**
     * @testdox Calculations for interest, principal and periods are correct
     */
    public function testSimpleInterest() : void
    {
        $P = 100.00;
        $r = 0.05;
        $t = 3;

        $I = $P * $r * $t;

        self::assertEqualsWithDelta($I, FinanceFormulas::getSimpleInterest($P, $r, $t), 0.01);
        self::assertEqualsWithDelta($P, FinanceFormulas::getSimpleInterestPrincipal($I, $r, $t), 0.01);
        self::assertEqualsWithDelta($r, FinanceFormulas::getSimpleInterestRate($I, $P, $t), 0.01);
        self::assertEquals($t, FinanceFormulas::getSimpleInterestTime($I, $P, $r));
    }

    /**
     * @testdox The descounted payback period is correct
     */
    public function testDiscountedPaybackPeriod() : void
    {
        $O1 = 5000;
        $r  = 0.05;
        $CF = 1000;

        self::assertEqualsWithDelta(5.896, FinanceFormulas::getDiscountedPaybackPeriod($CF, $O1, $r), 0.01);
    }

    /**
     * @testdox Test the correct calculation of the growth rate in order to double and vice versa
     */
    public function testDoublingTime() : void
    {
        $r = 0.05;

        self::assertEqualsWithDelta(14.207, FinanceFormulas::getDoublingTime($r), 0.01);
        self::assertEqualsWithDelta($r, FinanceFormulas::getDoublingRate(14.207), 0.01);
    }

    /**
     * @testdox Test the correct calculation of the growth rate in order to double and vice versa with continuous compounding
     */
    public function testDoublingTimeContinuousCompounding() : void
    {
        $r = 0.05;

        self::assertEqualsWithDelta(13.863, FinanceFormulas::getDoublingTimeContinuousCompounding($r), 0.01);
        self::assertEqualsWithDelta($r, FinanceFormulas::getDoublingContinuousCompoundingRate(13.863), 0.01);
    }

    public function testEquivalentAnnualAnnuity() : void
    {
        $npv = 1000;
        $r   = 0.15;
        $n   = 7;

        self::assertEqualsWithDelta(240.36, FinanceFormulas::getEquivalentAnnualAnnuity($npv, $r, $n), 0.01);
        self::assertEquals($n, FinanceFormulas::getPeriodsOfEAA(240.36, $npv, $r));
        self::assertEqualsWithDelta($npv, FinanceFormulas::getNetPresentValueOfEAA(240.36, $r, $n), 0.01);
    }

    public function testFreeCashFlowToEquity() : void
    {
        $income    = 1000;
        $depamo    = 300;
        $capital   = 400;
        $wc        = 200;
        $borrowing = 500;

        self::assertEqualsWithDelta(1200, FinanceFormulas::getFreeCashFlowToEquity($income, $depamo, $capital, $wc, $borrowing), 0.01);
    }

    public function testFreeCashFlowToFirm() : void
    {
        $ebit    = 1000;
        $depamo  = 300;
        $t       = 0.15;
        $capital = 400;
        $wc      = 200;

        self::assertEqualsWithDelta(550, FinanceFormulas::getFreeCashFlowToFirm($ebit, $t, $depamo, $capital, $wc), 0.01);
    }

    public function testFutureValue() : void
    {
        $c = 1000;
        $r = 0.15;
        $n = 7;

        self::assertEqualsWithDelta(2660.02, FinanceFormulas::getFutureValue($c, $r, $n), 0.01);
    }

    public function testFutureValueContinuousCompounding() : void
    {
        $pv = 1000;
        $r  = 0.15;
        $t  = 7;

        self::assertEqualsWithDelta(2857.65, FinanceFormulas::getFutureValueContinuousCompounding($pv, $r, $t), 0.01);
    }

    public function testValueFactor() : void
    {
        $r = 0.15;
        $n = 7;

        self::assertEqualsWithDelta(2.66, FinanceFormulas::getFutureValueFactor($r, $n), 0.01);
        self::assertEqualsWithDelta(0.37594, FinanceFormulas::getPresentValueFactor($r, $n), 0.01);
    }

    public function testGeometricMeanReturn() : void
    {
        $r = [0.01, 0.02, 0.03, 0.04, 0.05, 0.06, 0.07];

        self::assertEqualsWithDelta(0.04123, FinanceFormulas::getGeometricMeanReturn($r), 0.01);
    }

    public function testGrowingAnnuityFV() : void
    {
        $p = 1000;
        $r = 0.15;
        $g = 0.1;
        $n = 7;

        self::assertEqualsWithDelta(14226.06, FinanceFormulas::getGrowingAnnuityFV($p, $r, $g, $n), 0.01);
    }

    public function testGrowingAnnuityPaymentPV() : void
    {
        $p = 1000;
        $r = 0.15;
        $g = 0.1;
        $n = 7;

        self::assertEqualsWithDelta(186.98, FinanceFormulas::getGrowingAnnuityPaymentPV($p, $r, $g, $n), 0.01);
    }

    public function testGrowingAnnuityPaymentFV() : void
    {
        $fv = 1000;
        $r  = 0.15;
        $g  = 0.1;
        $n  = 7;

        self::assertEqualsWithDelta(70.29, FinanceFormulas::getGrowingAnnuityPaymentFV($fv, $r, $g, $n), 0.01);
    }

    public function testGrowingAnnuityPV() : void
    {
        $p = 1000;
        $r = 0.15;
        $g = 0.1;
        $n = 7;

        self::assertEqualsWithDelta(5348.1, FinanceFormulas::getGrowingAnnuityPV($p, $r, $g, $n), 0.01);
    }

    public function testGrowingPerpetuityPV() : void
    {
        $d = 1000;
        $r = 0.15;
        $g = 0.1;

        self::assertEqualsWithDelta(20000, FinanceFormulas::getGrowingPerpetuityPV($d, $r, $g), 0.01);
    }

    public function testNetPresentValue() : void
    {
        $c = [1000, 100, 200, 300, 400, 500, 600];
        $r = 0.15;

        self::assertEqualsWithDelta(172.13, FinanceFormulas::getNetPresentValue($c, $r), 0.01);
    }

    public function testInvalidNetPresentValue() : void
    {
        self::expectException(\UnexpectedValueException::class);

        FinanceFormulas::getNetPresentValue([], 0.1);
    }

    public function testRealRateOfReturn() : void
    {
        $nominal   = 0.15;
        $inflation = 0.05;

        self::assertEqualsWithDelta(0.09524, FinanceFormulas::getRealRateOfReturn($nominal, $inflation), 0.01);
    }

    public function testNetWorkingCapital() : void
    {
        self::assertEqualsWithDelta(1000 - 600, FinanceFormulas::getNetWorkingCapital(1000, 600), 0.01);
    }

    public function testNumberOfPeriodsPVFV() : void
    {
        $fv = 1200;
        $pv = 1000;
        $r  = 0.03;

        self::assertEqualsWithDelta(6.1681, FinanceFormulas::getNumberOfPeriodsPVFV($fv, $pv, $r), 0.01);
    }

    public function testPresentValue() : void
    {
        $c = 1000;
        $r = 0.15;
        $n = 7;

        self::assertEqualsWithDelta(375.94, FinanceFormulas::getPresentValue($c, $r, $n), 0.01);
    }

    public function testPresentValueContinuousCompounding() : void
    {
        $c = 1000;
        $r = 0.15;
        $t = 7;

        self::assertEqualsWithDelta(349.94, FinanceFormulas::getPresentValueContinuousCompounding($c, $r, $t), 0.01);
    }
}
