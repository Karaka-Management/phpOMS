<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Business\Finance
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Business\Finance;

/**
 * Finance class.
 *
 * @package phpOMS\Business\Finance
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @SuppressWarnings(PHPMD.CamelCaseParameterName)
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 */
final class Loan
{
    /**
     * Constructor
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Balloon Loan - Payments
     *
     * @param float $PV      Present value
     * @param float $r       Rate per period
     * @param int   $n       Number of periods
     * @param float $balloon Balloon balance
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPaymentsOnBalloonLoan(float $PV, float $r, int $n, float $balloon = 0.0) : float
    {
        return ($PV - $balloon / \pow(1 + $r, $n)) * $r / (1 - \pow(1 + $r, -$n));
    }

    /**
     * Loan - Balloon Balance
     *
     * @param float $PV Present value (original balance)
     * @param float $P  Payment
     * @param float $r  Rate per payment
     * @param int   $n  Number of payments
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getBalloonBalanceOfLoan(float $PV, float $P, float $r, int $n) : float
    {
        return $PV * \pow(1 + $r, $n) - $P * (\pow(1 + $r, $n) - 1) / $r;
    }

    /**
     * Loan - Payment
     *
     * @param float $PV Present value (original balance)
     * @param float $r  Rate per period
     * @param int   $n  Number of periods
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getLoanPayment(float $PV, float $r, int $n) : float
    {
        return $r * $PV / (1 - \pow(1 + $r, -$n));
    }

    /**
     * Loan - Remaining Balance
     *
     * @param float $PV Present value (original balance)
     * @param float $P  Payment
     * @param float $r  Rate per payment
     * @param int   $n  Number of payments
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getRemainingBalanceLoan(float $PV, float $P, float $r, int $n) : float
    {
        return $PV * \pow(1 + $r, $n) - $P * (\pow(1 + $r, $n) - 1) / $r;
    }

    /**
     * Loan to Deposit Ratio
     *
     * @param float $loans    Loans
     * @param float $deposits Deposits
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getLoanToDepositRatio(float $loans, float $deposits) : float
    {
        return $loans / $deposits;
    }

    /**
     * Loan to Value (LTV)
     *
     * @param float $loan       Loan amount
     * @param float $collateral Value of collateral
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getLoanToValueRatio(float $loan, float $collateral) : float
    {
        return $loan / $collateral;
    }

    /**
     * Calculate the payment for amortization loans (interest + principal)
     *
     * @param float $loan     Loan amount
     * @param float $r        Rate
     * @param int   $duration Loan duration
     * @param int   $interval Payment interval
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getAmortizationLoanPayment(float $loan, float $r, int $duration, int $interval) : float
    {
        return $loan * (($r / $interval * (1.0 + $r / $interval) / $duration) / ((1.0 + $r / $interval) / $duration) - 1);
    }

    /**
     * Calculate the interest for amortization loans
     *
     * @param float $loan     Loan amount
     * @param float $r        Rate
     * @param int   $interval Payment interval
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getAmortizationLoanInterest(float $loan, float $r, int $interval) : float
    {
        return $loan * $r / $interval;
    }

    /**
     * Calculate the principal for amortization loans
     *
     * @param float $payment  Total payment
     * @param float $interest Interest payment
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getAmortizationPrincipalPayment(float $payment, float $interest) : float
    {
        return $payment - $interest;
    }

    /**
     * Calculate schedule for amortization loan
     *
     * @param float $loan     Loan amount
     * @param float $r        Borrowing rate (annual)
     * @param int   $duration Loan duration in months
     * @param int   $interval Payment interval (usually 12 = every month)
     */
    public static function getAmortizationSchedule(float $loan, float $r, int $duration, int $interval) : array
    {
        $schedule = [0 => ['loan' => $loan, 'total' => 0.0, 'interest' => 0.0, 'principal' => 0.0]];
        $previous = \reset($schedule);

        while ($previous['loan'] > 0.0) {
            $new = [
                'loan'      => 0.0,
                'total'     => 0.0,
                'interest'  => 0.0,
                'principal' => 0.0,
            ];

            $new['total']     = \round(self::getAmortizationLoanPayment($previous['loan'], $r, $duration, $interval), 2);
            $new['interest']  = \round(self::getAmortizationLoanInterest($previous['loan'], $r, $interval), 2);
            $new['principal'] = \round($new['total'] - $new['interest'], 2);
            $new['loan']      = \max(0, \round($previous['loan'] - $new['principal'], 2));

            if ($new['loan'] < 0.01) {
                $new['loan'] = 0.0;
            }

            $schedule[] = $new;
            $previous   = $new;
        }

        return $schedule;
    }
}
