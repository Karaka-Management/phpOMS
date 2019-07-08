<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Business\Finance;

use phpOMS\Business\Finance\Loan;

/**
 * @internal
 */
class LoanTest extends \PHPUnit\Framework\TestCase
{
    public function testRatios() : void
    {
        self::assertEquals(100 / 50, Loan::getLoanToDepositRatio(100, 50));
        self::assertEquals(100 / 50, Loan::getLoanToValueRatio(100, 50));
    }

    public function testPaymentsOnBalloonLoan() : void
    {
        $pv      = 1000;
        $r       = 0.15;
        $n       = 7;
        $balloon = 300;

        self::assertEqualsWithDelta(213.25, Loan::getPaymentsOnBalloonLoan($pv, $r, $n, $balloon), 0.01);
    }

    public function testBalloonBalanceOfLoan() : void
    {
        $pv = 1000;
        $p  = 300;
        $r  = 0.15;
        $n  = 7;

        self::assertEqualsWithDelta(-660.02, Loan::getBalloonBalanceOfLoan($pv, $p, $r, $n), 0.01);
    }

    public function testLoanPayment() : void
    {
        $pv = 1000;
        $r  = 0.15;
        $n  = 7;

        self::assertEqualsWithDelta(240.36, Loan::getLoanPayment($pv, $r, $n), 0.01);
    }

    public function testRemainingBalanceLoan() : void
    {
        $pv = 1000;
        $p  = 200;
        $r  = 0.15;
        $n  = 7;

        self::assertEqualsWithDelta(446.66, Loan::getRemainingBalanceLoan($pv, $p, $r, $n), 0.01);
    }
}
