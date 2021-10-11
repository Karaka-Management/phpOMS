<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Math\Statistic\Forecast
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Statistic\Forecast;

use phpOMS\Math\Statistic\Average;
use phpOMS\Math\Statistic\Correlation;
use phpOMS\Math\Statistic\MeasureOfDispersion;
use phpOMS\Utils\ArrayUtils;

/**
 * Basic forecast functions.
 *
 * @package phpOMS\Math\Statistic\Forecast
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Error
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Get the error of a forecast.
     *
     * @param float $observed   Dataset
     * @param float $forecasted Forecasted
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getForecastError(float $observed, float $forecasted) : float
    {
        return $observed - $forecasted;
    }

    /**
     * Get array of errors of a forecast.
     *
     * @param float[] $observed   Dataset
     * @param float[] $forecasted Forecasted
     *
     * @return float[]
     *
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public static function getPercentageError(float $error, float $observed) : float
    {
        return $error / $observed;
    }

    /**
     * Get error percentages.
     *
     * @param float[] $errors   Errors
     * @param float[] $observed Dataset
     *
     * @return float[]
     *
     * @since 1.0.0
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
     * @param array<int, int|float> $errors Errors
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMeanAbsoulteError(array $errors) : float
    {
        return MeasureOfDispersion::meanAbsoluteDeviation($errors);
    }

    /**
     * Get mean absolute deviation (MAD).
     *
     * @param array<int, int|float> $observed   Observed values
     * @param array<int, int|float> $forecasted Forecasted values
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMeanAbsoulteDeviation(array $observed, array $forecasted) : float
    {
        $deviation = 0.0;
        foreach ($observed as $key => $value) {
            $deviation += abs($value - $forecasted[$key]);
        }

        return $deviation / \count($observed);
    }

    /**
     * Get mean squared error (MSE).
     *
     * @param array<int, int|float> $errors Errors
     * @param int                   $offset Population/Size offset
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMeanSquaredError(array $errors, int $offset = 0) : float
    {
        return MeasureOfDispersion::squaredMeanDeviation($errors, null, $offset);
    }

    /**
     * Get root mean squared error (RMSE).
     *
     * @param array<int, int|float> $errors Errors
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getRootMeanSquaredError(array $errors) : float
    {
        return sqrt(Average::arithmeticMean(ArrayUtils::power($errors, 2)));
    }

    /**
     * Goodness of fit (R-squared)
     *
     * Evaluating how well the observed data fit the linear regression model.
     *
     * @latex R^{2} = \frac{\sum \left(\hat{y}_{i} - \bar{y}\right)^2}{\sum \left(y_{i} - \bar{y}\right)^2}
     *
     * @param float[] $observed   Obersved y values
     * @param float[] $forecasted Forecasted y values
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getCoefficientOfDetermination(array $observed, array $forecasted) : float
    {
        return Correlation::bravaisPersonCorrelationCoefficientPopulation($observed, $forecasted) ** 2;
    }

    /**
     * Get sum squared error (SSE).
     *
     * @param array<int, int|float> $errors Errors
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSumSquaredError(array $errors) : float
    {
        $error = 0.0;

        foreach ($errors as $e) {
            $error += $e * $e;
        }

        return $error;
    }

    /**
     * Get Adjusted coefficient of determination (R Bar Squared)
     *
     * @param float $R            R
     * @param int   $observations Amount of observations
     * @param int   $predictors   Amount of predictors
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getAdjustedCoefficientOfDetermination(float $R, int $observations, int $predictors) : float
    {
        return 1 - (1 - $R) * ($observations - 1) / ($observations - $predictors - 1);
    }

    /**
     * Get mean absolute percentage error (MAPE).
     *
     * @param float[] $observed   Dataset
     * @param float[] $forecasted Forecasted
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMeanAbsolutePercentageError(array $observed, array $forecasted) : float
    {
        return Average::arithmeticMean(ArrayUtils::abs(self::getPercentageErrorArray(self::getForecastErrorArray($observed, $forecasted), $observed)));
    }

    /**
     * Get mean absolute percentage error (sMAPE).
     *
     * @param float[] $observed   Dataset
     * @param float[] $forecasted Forecasted
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSymmetricMeanAbsolutePercentageError(array $observed, array $forecasted) : float
    {
        $error = [];

        foreach ($observed as $key => $value) {
            $error[] = abs($value - $forecasted[$key]) / ($value + $forecasted[$key]) / 2;
        }

        return Average::arithmeticMean($error);
    }

    /**
     * Get mean absolute scaled error (MASE)
     *
     * @param array<int, int|float> $scaledErrors Scaled errors
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMeanAbsoluteScaledError(array $scaledErrors) : float
    {
        return Average::arithmeticMean(ArrayUtils::abs($scaledErrors));
    }

    /**
     * Get mean squared scaled error (MSSE)
     *
     * @param array<int, int|float> $scaledErrors Scaled errors
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMeanSquaredScaledError(array $scaledErrors) : float
    {
        return Average::arithmeticMean(ArrayUtils::power($scaledErrors, 2));
    }

    /**
     * Get scaled error (SE)
     *
     * @param array<int, int|float> $errors   Errors
     * @param float[]               $observed Dataset
     * @param int                   $m        Shift
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function getScaledErrorArray(array $errors, array $observed, int $m = 1) : array
    {
        $scaled = [];
        $naive  = 1 / (\count($observed) - $m) * self::getNaiveForecast($observed, $m);

        foreach ($errors as $error) {
            $scaled[] = $error / $naive;
        }

        return $scaled;
    }

    /**
     * Get scaled error (SE)
     *
     * @param float   $error    Errors
     * @param float[] $observed Dataset
     * @param int     $m        Shift
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getScaledError(float $error, array $observed, int $m = 1) : float
    {
        return $error / (1 / (\count($observed) - $m) * self::getNaiveForecast($observed, $m));
    }

    /**
     * Get naive forecast
     *
     * @param float[] $observed Dataset
     * @param int     $m        Shift
     *
     * @return float
     *
     * @since 1.0.0
     */
    private static function getNaiveForecast(array $observed, int $m = 1) : float
    {
        $sum   = 0.0;
        $count = \count($observed);

        for ($i = 0 + $m; $i < $count; ++$i) {
            $sum += abs($observed[$i] - $observed[$i - $m]);
        }

        return $sum;
    }
}
