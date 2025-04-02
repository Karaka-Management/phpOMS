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

use phpOMS\Business\Finance\Loan;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Business\Finance\LoanTest: Loan formulas')]
final class LoanTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The loan to deposit ratio is correct')]
    public function testLoanToDepositRatio() : void
    {
        self::assertEquals(100 / 50, Loan::getLoanToDepositRatio(100, 50));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The loan to value ratio is correct')]
    public function testLoanToValueRatio() : void
    {
        self::assertEquals(100 / 50, Loan::getLoanToValueRatio(100, 50));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The balloon loan payments are correct for a given balloon')]
    public function testPaymentsOnBalloonLoan() : void
    {
        $pv      = 1000;
        $r       = 0.15;
        $n       = 7;
        $balloon = 300;

        self::assertEqualsWithDelta(213.25, Loan::getPaymentsOnBalloonLoan($pv, $r, $n, $balloon), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The balloon loan residual value (balloon) is correct for given payments')]
    public function testBalloonBalanceOfLoan() : void
    {
        $pv = 1000;
        $p  = 300;
        $r  = 0.15;
        $n  = 7;

        self::assertEqualsWithDelta(-660.02, Loan::getBalloonBalanceOfLoan($pv, $p, $r, $n), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The loan payments are correct for a given interest rate and period [continuous compounding]')]
    public function testLoanPayment() : void
    {
        $pv = 1000;
        $r  = 0.15;
        $n  = 7;

        self::assertEqualsWithDelta(240.36, Loan::getLoanPayment($pv, $r, $n), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The residual value is correct for a given payment amount, interest rate and period [continuous compounding]')]
    public function testRemainingBalanceLoan() : void
    {
        $pv = 1000;
        $p  = 200;
        $r  = 0.15;
        $n  = 7;

        self::assertEqualsWithDelta(446.66, Loan::getRemainingBalanceLoan($pv, $p, $r, $n), 0.01);
    }
}
