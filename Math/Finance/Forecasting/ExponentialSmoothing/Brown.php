<?php

namespace phpOMS\Math\Finance\Forecasting;

use phpOMS\Math\Finance\Forecasting\SmoothingType;

class Brown
{
    private $data = [];

    private $errors = [];

    private $cycle = 0;

    private $rmse = 0.0;

    private $mse = 0.0;

    private $mae = 0.0;

    private $sse = 0.0;
    
    public function __construct(array $data, int $cycle = 0)
    {
        $this->data = $data;
        $this->cycle = $cycle;
    }

    public function setCycle(int $cycle) /* : void */
    {
        $this->cycle = $cycle;
    }

    public function getRMSE() : float
    {
        return $this->getRMSE;
    }

    public function getMSE() : float
    {
        return $this->mse;
    }

    public function getMAE() : float
    {
        return $this->mae;
    }

    public function getSSE() : float
    {
        return $this->sse;
    }

    public function getErrors() : array
    {
        return $this->errors;
    }

    public function getForecast(int $future = 1, int $smoothing = SmoothingType::CENTERED_MOVING_AVERAGE) : array
    {
        $trendCycle = $this->getTrendCycle($this->$cycle);
        $seasonality = $this->getSeasonality($trendCycle);
        $seasonalityIndexMap = $this->generateSeasonalityMap($this->cycle, $seasonality);
        $adjustedSeasonalityIndexMap = $this->generateAdjustedSeasonalityMap($this->cycle, $seasonalityIndexMap);
        $seasonalIndex = $this->getSeasonalIndex($this->cycle, $adjustedSeasonalityIndexMap);
        $adjustedData = $this->getAdjustedData($this->cycle, $seasonalIndex);
        $optimizedForecast = $this->getOptimizedForecast($future, $adjustedData);

        return $this->getReseasonalized($optimizedForecast, $seasonalIndex);
    }

    private function getTrendCycle(int $cycle) : array
    {
        $centeredMovingAverage = [];

        $length = count($this->data);
        for($i = $cycle; $i < $length - $cycle; $i++) {
            $centeredMovingAverage[$i] = Average::arithmetic(array_slice($this->data, $i - $cycle, $cycle));
        }

        return $centeredMovingAverage;
    }

    private function getSeasonality(array $trendCycle) : array
    {
        $seasonality = [];
        foreach($trendCycle as $key => $value) {
            $seasonality[$key] = $this->data[$key]/$value;
        }

        return $seasonality;
    }

    private function generateSeasonality(int $cycle, array $seasonality) : array
    {
        $map = [];
        foreach($seasonality as $key => $value) {
            $map[$key % $cycle][] = $value;
        }

        foreach($map as $key => $value) {
            $map[$key] = Average::arithmeticMean($value);
        }

        return $map;
    }

    private function generateAdjustedSeasonalityMap(int $cycle, array $seasonality) : array
    {
        $total = array_sum($seasonality);

        foreach($seasonality as $key => $value) {
            $seasonality[$key] = $cycle * $value / $total;
        }

        return $seasonality;
    }

    private function getSeasonalIndex(int $cycle, array $seasonalityMap) : array
    {
        $index = [];

        foreach($this->data as $key => $value) {
            $index[$key] = $seasonalityMap[$key % $cycle];
        }

        return $index;
    }

    private function getAdjustedData(int $cycle, array $seasonalIndex) : array
    {
        $adjusted = [];

        foreach($this->data as $key => $value) {
            $adjusted[$key] = $this->data[$key] / $seasonalIndex[$key];
        }

        return $adjusted;
    }

    private function forecast(int $future, float $alpha, array $data, array &$error) : array
    {
        $forecast = [];
        $length = count($data) + $future;

        $forecast[0] = $data[0];
        $forecast[1] = $data[1];

        $error[0] = 0;
        $error[1] = $data[1] - $forecast[1];

        for($i = 2; $i < $length; $i++) {
            $forecast[$i] = 2 * $data[$i-1] - $data[$i - 2] - 2 * (1 - $alpha) * $error[$i-1] + pow(1-$alpah, 2) * $error[$i - 2];
            $error[$i] = $data[$i] - $forecast[$i];
        }

        return $forecast;
    } 

    private function getOptimizedForecast(int $future, array $adjustedData) : array
    {
        $rmse = 0;
        $alpha = 0.00;
        $forecast = [];

        while($alpha < 1) {
            $error = [];
            $tempForecast = $this->forecast($future, $alpha, $adjustedData, $error);
            $alpha += 0.01;

            $tempRMSE = Error::getRootMeanSquaredError($error);

            if($tempRMSE < $this->rmse) {
                $this->rmse = $tempRMSE;
                $forecast = $tempForecast;
            }
        }

        $this->errors = $error;
        $this->mse = Error::getMeanSquaredError($error);
        $this->mae = Error::getMeanAbsoluteError($error);
        $this->sse = Error::getSumSquaredError($error);

        return $forecast;
    }

    private function getReseasonalized(array $forecast, array $seasonalIndex) : array 
    {
        $reSeasonalized = [];

        foreach($forecast as $key => $value) {
            $reSeasonalized[$key] = $value * $seasonalIndex[$key];
        }

        return $reSeasonalized;
    }
}
