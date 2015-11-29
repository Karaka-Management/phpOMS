<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Math\Finance;

class FinanceFormulas
{

    /**
     * @param \float $r Stated annual interest rate
     * @param \int   $n number of times compounded
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getAnnualPercentageYield(\float $r, \int $n) : \float
    {
        return pow(1 + $r / $n, $n) - 1;
    }

    /**
     * @param \float $apy Annual percentage yield
     * @param \int   $n   Number of times compounded
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getStateAnnualInterestRateOfAPY(\float $apy, \int $n) : \float
    {
        return (pow($apy + 1, 1 / $n) - 1) * $n;
    }

    /**
     * @param \float $apy Annual percentage yield
     * @param \float $r   Stated annual interest rate
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getNumberOfCompoundsOfAPY(\float $apy, \float $r) : \float
    {
        return 0.0;
    }

    /**
     * @param \float $P Periodic payment
     * @param \float $r Stated annual interest rate
     * @param \int   $n Number of periods
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getFutureValueOfAnnuity(\float $P, \float $r, \int $n) : \float
    {
        return $P * (pow(1 + $r, $n) - 1) / $r;
    }

    /**
     * @param \float $fva Future value annuity
     * @param \float $P   Periodic payment
     * @param \float $r   Stated annual interest rate
     *
     * @return \int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getNumberOfPeriodsOfFVA(\float $fva, \float $P, \float $r) : \int
    {
        return (int) round(log($fva / $P * $r + 1) / log(1 + $r));
    }

    /**
     * @param \float $fva Future value annuity
     * @param \float $r   Stated annual interest rate
     * @param \int   $n   Number of periods
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getPeriodicPaymentOfFVA(\float $fva, \float $r, \int $n) : \float
    {
        return $fva / ((pow(1 + $r, $n) - 1) / $r);
    }

    /**
     * @param \float $fva Future value annuity
     * @param \float $P   Periodic payment
     * @param \int   $n   Number of periods
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getRateOfFVA(\float $fva, \float $P, \int $n) : \float
    {
        return 0.0;
    }

    /**
     * @param \float $cf Cach flow
     * @param \float $r  Rate
     * @param \int   $t  Time
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getFutureValueOfAnnuityConinuousCompounding(\float $cf, \float $r, \int $t) : \float
    {
        return $cf * (exp($r * $t) - 1) / (exp($r) - 1);
    }

    /**
     * @param \float $fvacc Future value annuity continuous compoinding
     * @param \float $r     Rate
     * @param \int   $t     Time
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getCashFlowOfFVACC(\float $fvacc, \float $r, \int $t) : \float
    {
        return $fvacc / ((exp($r * $t) - 1) / (exp($r) - 1));
    }

    /**
     * @param \float $fvacc Future value annuity continuous compoinding
     * @param \float $cf    Cach flow
     * @param \int   $t     Time
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getRateOfFVACC(\float $fvacc, \float $cf, \int $t) : \float
    {
        return 0.0;
    }

    /**
     * @param \float $fvacc Future value annuity continuous compoinding
     * @param \float $cf    Cach flow
     * @param \float $r     Rate
     *
     * @return \int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getTimeOfFVACC(\float $fvacc, \float $cf, \float $r) : \int
    {
        return (int) round(log($fvacc / $cf * (exp($r) - 1) + 1) / $r);
    }

    /**
     * @param \float $pv Present value
     * @param \float $r  Rate per period
     * @param \int   $n  Number of periods
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getAnnuityPaymentPV(\float $pv, \float $r, \int $n) : \float
    {
        return $r * $pv / (1 - pow(1 + $r, -$n));
    }

    /**
     * @param \float $p  Payment
     * @param \float $pv Present value
     * @param \float $r  Rate per period
     *
     * @return \int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getNumberOfAPPV(\float $p, \float $pv, \float $r) : \int
    {
        return (int) round(-log(-($r * $pv / $p - 1)) / log(1 + $r));
    }

    /**
     * @param \float $p  Payment
     * @param \float $pv Present value
     * @param \int   $n  Number of periods
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getRatePerPeriodOfAPPV(\float $p, \float $pv, \int $n) : \float
    {
        return 0.0;
    }

    /**
     * @param \float $p Payment
     * @param \float $r Rate per period
     * @param \int   $n Number of periods
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getPresentValueOfAPPV(\float $p, \float $r, \int $n) : \float
    {
        return $p / $r * (1 - pow(1 + $r, -$n));
    }

    /**
     * @param \float $fv Present value
     * @param \float $r  Rate per period
     * @param \int   $n  Number of periods
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getAnnuityPaymentFV(\float $fv, \float $r, \int $n) : \float
    {
        return $r * $fv / (pow(1 + $r, $n) - 1);
    }

    /**
     * @param \float $p  Payment
     * @param \float $fv Present value
     * @param \float $r  Rate per period
     *
     * @return \int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getNumberOfAPFV(\float $p, \float $fv, \float $r) : \int
    {
        return (int) round(log($fv * $r / $p + 1) / log(1 + $r));
    }

    /**
     * @param \float $p  Payment
     * @param \float $fv Present value
     * @param \int   $n  Rate per period
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getRatePerPeriodOfAPFV(\float $p, \float $fv, \int $n) : \float
    {
        return 0.0;
    }

    /**
     * @param \float $p Payment
     * @param \float $r Present value
     * @param \int   $n Rate per period
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getFutureValueOfAPFV(\float $p, \float $r, \int $n) : \float
    {
        return $p / $r * (pow(1 + $r, $n) - 1);
    }

    /**
     * @param \float $r Rate per period
     * @param \int   $n Number of periods
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getAnnutiyPaymentFactorPV(\float $r, \int $n) : \float
    {
        return $r / (1 - pow(1 + $r, -$n));
    }

    /**
     * @param \float $p Payment factor
     * @param \int   $n Number of periods
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getRatePfAPFPV(\float $p, \int $n) : \float
    {
        return 0.0;
    }

    /**
     * @param \float $p Payment factor
     * @param \float $r Rate per period
     *
     * @return \int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getNumberOfAPFPV(\float $p, \float $r) : \int
    {
        return (int) round(-log(-($r / $p - 1)) / log(1 + $r));
    }

    /**
     * @param \float $P Periodic payment
     * @param \float $r Stated annual interest rate
     * @param \int   $n Number of periods
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getPresentValueOfAnnuity(\float $P, \float $r, \int $n) : \float
    {
        return $P * (1 - pow(1 + $r, -$n)) / $r;
    }

    /**
     * @param \float $pva Future value annuity
     * @param \float $P   Periodic payment
     * @param \float $r   Stated annual interest rate
     *
     * @return \int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getNumberOfPeriodsOfPVA(\float $pva, \float $P, \float $r) : \int
    {
        return (int) round(-log(-($pva / $P * $r - 1)) / log(1 + $r));
    }

    /**
     * @param \float $pva Future value annuity
     * @param \float $r   Stated annual interest rate
     * @param \int   $n   Number of periods
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getPeriodicPaymentOfPVA(\float $pva, \float $r, \int $n) : \float
    {
        return $pva / ((1 - pow(1 + $r, -$n)) / $r);
    }

    /**
     * @param \float $pva Future value annuity
     * @param \float $P   Periodic payment
     * @param \int   $n   Number of periods
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getRateOfPVA(\float $pva, \float $P, \int $n) : \float
    {
        return 0.0;
    }

    /**
     * @param \float $r Rate per period
     * @param \int   $n Number of periods
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getPresentValueAnnuityFactor(\float $r, \int $n) : \float
    {
        return (1 - pow(1 + $r, -$n)) / $r;
    }

    /**
     * @param \float $p Payment factor
     * @param \int   $n Number of periods
     *
     * @return \float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getRateOfPVAF(\float $p, \int $n) : \float
    {
        return 0.0;
    }

    /**
     * @param \float $p Payment factor
     * @param \float $r Number of periods
     *
     * @return \int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getPeriodsOfPVAF(\float $p, \float $r) : \int
    {
        return (int) round(-log(-($p * $r - 1)) / log(1 + $r));
    }
}
