<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Statistic\Forecast
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Statistic\Forecast;

/**
 * General forecasts helper class.
 *
 * @package phpOMS\Math\Statistic\Forecast
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Forecasts
{
    /**
     * Get forecast/prediction interval.
     *
     * @param float $forecast          Forecast value
     * @param float $standardDeviation Standard Deviation of forecast
     * @param float $interval          Forecast multiplier for prediction intervals
     *
     * @return array<int|float>
     *
     * @since 1.0.0
     */
    public static function getForecastInteval(float $forecast, float $standardDeviation, float $interval = 1.96) : array
    {
        return [$forecast - $interval * $standardDeviation, $forecast + $interval * $standardDeviation];
    }

    public static function simpleSeasonalForecast(array $history, int $periods, int $seasonality = 1) : array
    {
        $avg = \array_sum($history) / \count($history);

        $variance = 0;
        foreach ($history as $sale) {
            $variance += \pow($sale - $avg, 2);
        }

        $variance /= \count($history);
        $stdDeviation = \sqrt($variance);

        // Calculate the seasonal index for each period
        $seasonalIndex = [];
        for ($i = 0; $i < $seasonality; $i++) {
            $seasonalIndex[$i] = 0;
            $count = 0;

            for ($j = $i; $j < \count($history); $j += $seasonality) {
                $seasonalIndex[$i] += $history[$j];
                $count++;
            }

            if ($count > 0) {
                $seasonalIndex[$i] /= $count;
                $seasonalIndex[$i] /= $avg;
            }
        }

        // Forecast the next periods
        $forecast = [];
        for ($i = 1; $i <= $periods; $i++) {
            $seasonalMultiplier = $seasonalIndex[($i - 1) % $seasonality];
            $forecast[] = $avg * $seasonalMultiplier + ($stdDeviation * $i);
        }

        return $forecast;
    }
}
