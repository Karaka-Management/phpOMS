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

namespace phpOMS\Math\Forecast;

use phpOMS\Math\Functions;
use phpOMS\Math\Statistic\Average;
use phpOMS\Math\Statistic\MeasureOfDispersion;

/**
 * Basic forecast functions.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Error
{
    /**
     * Get the error of a forecast.
     *
     * @param float $observed   Dataset
     * @param float $forecasted Forecasted
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getForecastError(float $observed, float $forecasted) : float
    {
        return $observed - $forecasted;
    }

    /**
     * Get array of errors of a forecast.
     *
     * @param array $observed   Dataset
     * @param array $forecasted Forecasted
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getForecastErrorArray(array $observed, array $forecasted) : array
    {
        $errors = [];

        foreach ($forecasted as $key => $expected) {
            $errors[] = self::getForecastError($observed[$key], $expected);
        }

        return $errors;
    }

    /**
     * Get error percentage.
     *
     * @param float $error    Error
     * @param float $observed Dataset
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getPercentageError(float $error, float $observed) : float
    {
        return $error / $observed;
    }

    /**
     * Get error percentages.
     *
     * @param array $errors   Errors
     * @param array $observed Dataset
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getPercentageErrorArray(array $errors, array $observed) : array
    {
        $percentages = [];

        foreach ($errors as $key => $error) {
            $percentages[] = self::getPercentageError($error, $observed[$key]);
        }

        return $percentages;
    }

    /**
     * Get mean absolute error (MAE).
     *
     * @param array $errors Errors
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getMeanAbsoulteError(array $errors) : float
    {
        return Average::arithmeticMean(Functions::abs($errors));
    }

    /**
     * Get root mean squared error (RMSE).
     *
     * @param array $errors Errors
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getRootMeanSquaredError(array $errors) : float
    {
        return sqrt(Average::arithmeticMean(self::square($errors)));
    }

    /**
     * Get mean absolute percentage error (MAPE).
     *
     * @param array $observed   Dataset
     * @param array $forecasted Forecasted
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getMeanAbsolutePercentageError(array $observed, array $forecasted) : float
    {
        return Average::arithmeticMean(Functions::abs(self::getPercentageErrorArray(self::getForecastErrorArray($observed, $forecasted), $observed)));
    }

    /**
     * Get mean absolute percentage error (sMAPE).
     *
     * @param array $observed   Dataset
     * @param array $forecasted Forecasted
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getSymmetricMeanAbsolutePercentageError(array $observed, array $forecasted) : float
    {
        $error = [];

        foreach ($observed as $key => $value) {
            $error[] = 200 * abs($value - $forecasted[$key]) / ($value + $forecasted[$key]);
        }

        return Average::arithmeticMean($error);
    }

    /**
     * Square all values in array.
     *
     * @param array $values Values to square
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     *         todo: move to utils?! implement sqrt for array as well... could be usefull for others (e.g. matrix)
     */
    private static function square(array $values) : array
    {
        $squared = [];

        foreach ($values as $value) {
            $squared[] = $value * $value;
        }

        return $squared;
    }

    /**
     * Get cross sectional scaled errors (CSSE)
     *
     * @param array $errors   Errors
     * @param array $observed Dataset
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getCrossSectionalScaledErrorArray(array $errors, array $observed) : array
    {
        $scaled    = [];
        $deviation = MeasureOfDispersion::meanDeviation($observed);

        foreach ($errors as $error) {
            $error[] = $error / $deviation;
        }

        return $scaled;
    }

    /**
     * Get cross sectional scaled errors (CSSE)
     *
     * @param float $error    Errors
     * @param array $observed Dataset
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getCrossSectionalScaledError(float $error, array $observed) : float
    {
        $mean = Average::arithmeticMean($observed);
        $sum  = 0.0;

        foreach ($observed as $value) {
            $sum += abs($value - $mean);
        }

        return $error / MeasureOfDispersion::meanDeviation($observed);
    }

    /**
     * Get mean absolute scaled error (MASE)
     *
     * @param array $scaledErrors Scaled errors
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getMeanAbsoluteScaledError(array $scaledErrors) : float
    {
        return Average::arithmeticMean(Functions::abs($scaledErrors));
    }

    /**
     * Get mean absolute scaled error (MASE)
     *
     * @param array $scaledErrors Scaled errors
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getMeanSquaredScaledError(array $scaledErrors) : float
    {
        return Average::arithmeticMean(self::square($scaledErrors));
    }

    /**
     * Get scaled error (SE)
     *
     * @param array $errors   Errors
     * @param array $observed Dataset
     * @param int   $m        Shift
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getScaledErrorArray(array $errors, array $observed, int $m = 1) : array
    {
        $scaled = [];
        $naive  = 1 / (count($observed) - $m) * self::getNaiveForecast($observed, $m);

        foreach ($errors as $error) {
            $error[] = $error / $naive;
        }

        return $scaled;
    }

    /**
     * Get scaled error (SE)
     *
     * @param float $error    Errors
     * @param array $observed Dataset
     * @param int   $m        Shift
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getScaledError(float $error, array $observed, int $m = 1) : float
    {
        return $error / (1 / (count($observed) - $m) * self::getNaiveForecast($observed, $m));
    }

    /**
     * Get naive forecast
     *
     * @param array $observed Dataset
     * @param int   $m        Shift
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function getNaiveForecast(array $observed, int $m = 1) : float
    {
        $sum   = 0.0;
        $count = count($observed);

        for ($i = 0 + $m; $i < $count; $i++) {
            $sum += abs($observed[$i] - $observed[$i - $m]);
        }

        return $sum;
    }
}