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

use phpOMS\Business\Finance\Loan;

/**
 * @testdox phpOMS\tests\Business\Finance\LoanTest: Loan formulas
 *
 * @internal
 */
class LoanTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The loan to deposit ratio is correct
     * @group framework
     */
    public function testLoanToDepositRatio() : void
    {
        self::assertEquals(100 / 50, Loan::getLoanToDepositRatio(100, 50));
    }

    /**
     * @testdox The loan to value ratio is correct
     * @group framework
     */
    public function testLoanToValueRatio() : void
    {
        self::assertEquals(100 / 50, Loan::getLoanToValueRatio(100, 50));
    }

    /**
     * @testdox The balloon loan payments are correct for a given balloon
     * @group framework
     */
    public function testPaymentsOnBalloonLoan() : void
    {
        $pv      = 1000;
        $r       = 0.15;
        $n       = 7;
        $balloon = 300;

        self::assertEqualsWithDelta(213.25, Loan::getPaymentsOnBalloonLoan($pv, $r, $n, $balloon), 0.01);
    }

    /**
     * @testdox The balloon loan residual value (balloon) is correct for given payments
     * @group framework
     */
    public function testBalloonBalanceOfLoan() : void
    {
        $pv = 1000;
        $p  = 300;
        $r  = 0.15;
        $n  = 7;

        self::assertEqualsWithDelta(-660.02, Loan::getBalloonBalanceOfLoan($pv, $p, $r, $n), 0.01);
    }

    /**
     * @testdox The loan payments are correct for a given interest rate and period [continuous compounding]
     * @group framework
     */
    public function testLoanPayment() : void
    {
        $pv = 1000;
        $r  = 0.15;
        $n  = 7;

        self::assertEqualsWithDelta(240.36, Loan::getLoanPayment($pv, $r, $n), 0.01);
    }

    /**
     * @testdox The residual value is correct for a given payment amount, interest rate and period [continuous compounding]
     * @group framework
     */
    public function testRemainingBalanceLoan() : void
    {
        $pv = 1000;
        $p  = 200;
        $r  = 0.15;
        $n  = 7;

        self::assertEqualsWithDelta(446.66, Loan::getRemainingBalanceLoan($pv, $p, $r, $n), 0.01);
    }
}
