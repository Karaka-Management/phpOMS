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

use phpOMS\Business\Finance\Loan;

class LoanTest extends \PHPUnit\Framework\TestCase
{
    public function testRatios()
    {
        self::assertEquals(100 / 50, Loan::getLoanToDepositRatio(100, 50));
        self::assertEquals(100 / 50, Loan::getLoanToValueRatio(100, 50));
    }

    public function testPaymentsOnBalloonLoan()
    {
        $pv      = 1000;
        $r       = 0.15;
        $n       = 7;
        $balloon = 300;

        self::assertEquals(213.25, Loan::getPaymentsOnBalloonLoan($pv, $r, $n, $balloon), '', 0.01);
    }

    public function testBalloonBalanceOfLoan()
    {
        $pv = 1000;
        $p  = 300;
        $r  = 0.15;
        $n  = 7;

        self::assertEquals(-660.02, Loan::getBalloonBalanceOfLoan($pv, $p, $r, $n), '', 0.01);
    }

    public function testLoanPayment()
    {
        $pv = 1000;
        $r  = 0.15;
        $n  = 7;

        self::assertEquals(240.36, Loan::getLoanPayment($pv, $r, $n), '', 0.01);
    }

    public function testRemainingBalanceLoan()
    {
        $pv = 1000;
        $p  = 200;
        $r  = 0.15;
        $n  = 7;

        self::assertEquals(446.66, Loan::getRemainingBalanceLoan($pv, $p, $r, $n), '', 0.01);
    }
}
