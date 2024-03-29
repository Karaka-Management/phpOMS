<?php
/**
 * Jingga
 *
 * PHP Version 8.1
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
}
