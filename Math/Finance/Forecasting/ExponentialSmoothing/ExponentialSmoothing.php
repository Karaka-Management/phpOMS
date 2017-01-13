@@ -1,265 +0,0 @@
<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
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
 namespace phpOMS\Math\Finance\Forecasting\ExponentialSmoothing;

use phpOMS\Math\Finance\Forecasting\SmoothingType;
use phpOMS\Math\Statistic\Average;
use phpOMS\Math\Statistic\Forecast\Error;

class Holt implements ExponentialSmoothingInterface
{
    private $data = [];

    private $errors = [];

    private $rmse = 0.0;

    private $mse = 0.0;

    private $mae = 0.0;

    private $sse = 0.0;

    public function __construct(array $data)
    {
        $this->data  = $data;
    }

    public function getRMSE() : float
    {
        return $this->rmse;
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

    public function forecast(int $future, int $trendType = TrendType::NONE, int $seasonalType = SeasonalType::NONE, int $cycle = 12, float $damping = 1) : array 
    {
        if($trendType === TrendType::ALL || $seasonalType === SeasonalType::ALL) {
            // todo: loob through all and find best
        } elseif($trendType === TrendType::NONE && $seasonalType === SeasonalType::NONE) {
            return $this->getNoneNone($future, $alpha);
        } elseif($trendType === TrendType::NONE && $seasonalType === SeasonalType::ADDITIVE) {
            return $this->getNoneAdditive($future, $alpha, $gamma, $cycle);
        } elseif($trendType === TrendType::NONE && $seasonalType === SeasonalType::MULTIPLICATIVE) {
            return $this->getNoneMultiplicative($future, $alpha, $gamma, $cycle);
        } elseif($trendType === TrendType::ADDITIVE && $seasonalType === SeasonalType::NONE) {
            return $this->getAdditiveNone($future, $alpha, $damping);
        } elseif($trendType === TrendType::ADDITIVE && $seasonalType === SeasonalType::ADDITIVE) {
            return $this->getAdditiveAdditive($future, $alpha, $beta, $gamma, $cycle, $damping);
        } elseif($trendType === TrendType::ADDITIVE && $seasonalType === SeasonalType::MULTIPLICATIVE) {
            return $this->getAdditiveMultiplicative($future, $alpha, $beta, $gamma, $cycle, $damping);
        } elseif($trendType === TrendType::MULTIPLICATIVE && $seasonalType === SeasonalType::NONE) {
            return $this->getMultiplicativeNone($future);
        } elseif($trendType === TrendType::MULTIPLICATIVE && $seasonalType === SeasonalType::ADDITIVE) {
            return $this->getMultiplicativeAdditive($future, $cycle, $damping);
        } elseif($trendType === TrendType::MULTIPLICATIVE && $seasonalType === SeasonalType::MULTIPLICATIVE) {
            return $this->getMultiplicativeMultiplicative($future, $cycle, $damping);
        }
    }

    private dampingSum(float $damping, int $length) : float
    {
        $sum = 0;
        for($i = 0; $i < $length; $i++) {
            $sum += pow($damping, $i);
        }

        return $sum;
    }

    public function getNoneNone($future, $alpha) : array 
    {
        $level[0] = $data[0];
        $dataLength = count($this->data) + $future;
        $forecast = [];

        for($i = 1; $i < $dataLength; $i++) {
            $level[] = $alpha * $this->data[$i-1] + (1 - $alpha) * $level[$i-1];

            $forecast[] = $level[$i];
        }

        return $forecast;
    }

    public function getNoneAdditive(int $future, float $alpha, float $gamma, int $cycle) : array 
    {
        $level[0] = $data[0];
        $dataLength = count($this->data) + $future;
        $forecast = [];
        $gamma_ = $gamma * (1 - $alpha);

        for($i = 0; $i < $cycle; $i++) {
            $seasonal[$i] = $data[$i] - $level[0];
        }

        for($i = 1; $i < $dataLength; $i++) {
            $hm = (int) floor(($i-1) % $cycle) + 1;

            $level[] = $alpha * ($this->data[$i-1] - $seasonal[$i]) + (1 - $alpha) * $level[$i-1];
            $seasonal[] = $gamma_*($this->data[$i-1] - $level[$i-1]) + (1 - $gamma_) * $seasonal[$i];

            $forecast[] = $level[$i] + $seasonal[$i+$hm];
        }

        return $forecast;
    }

    public function getNoneMultiplicative(int $future, float $alpha, float $gamma, int $cycle) : array 
    {
        $level[0] = $data[0];
        $dataLength = count($this->data) + $future;
        $forecast = [];
        $gamma_ = $gamma * (1 - $alpha);

        for($i = 0; $i < $cycle; $i++) {
            $seasonal[$i] = $this->data[$i] / $level[0];
        }

        for($i = 1; $i < $dataLength; $i++) {
            $hm = (int) floor(($i-1) % $cycle) + 1;

            $level[] = $alpha * ($this->data[$i-1] / $seasonal[$i]) + (1 - $alpha) * $level[$i-1];
            $seasonal[] = $gamma_*($this->data[$i-1] / $level[$i-1]) + (1 - $gamma_) * $seasonal[$i]

            $forecast[] = $level[$i] + $seasonal[$i+$hm];
        }

        return $forecast;
    }

    public function getAdditiveNone(int $future, float $alpha, float $damping) : array 
    {
        $level[0] = $this->data[0];
        $trend[0] = $this->data[1] - $this->data[0];
        $dataLength = count($this->data) + $future;
        $forecast = [];

        for($i = 1; $i < $dataLength; $i++) {
            $level[] = $alpha * $this->data[$i-1] + (1 - $alpha) * ($level[$i-1] + $damping * $trend[$i-1]);
            $trend[] = $beta * ($level[$i] - $level[$i-1]) + (1 - $beta) * $damping * $trend[$i-1];

            $forecast[] = $level[$i] + $this->dampingSum($damping, $i) * $trend[$i];
        }

        return $forecast;
    }

    public function getAdditiveAdditive(int $future, float $alpha, float $beta, float $gamma, int $cycle, float $damping) : array 
    {
        $level[0] = $1 / $cycle * array_sum($this->data, 0, $cycle);
        $trend[0] = 1 / $cycle;
        $dataLength = count($this->data) + $future;
        $forecast = [];
        $gamma_ = $gamma * (1 - $alpha);

        $sum = 0;
        for($i = 0; $i < $cycle; $i++) {
            $sum += ($this->data[$cycle] - $this->data[$i]) / $cycle;
        }

        $trend[0] *= $sum;

        for($i = 0; $i < $cycle; $i++) {
            $seasonal[$i] = $data[$i] - $level[0];
        }

        for($i = 1; $i < $dataLength; $i++) {
            $hm = (int) floor(($i-1) % $cycle) + 1;

            $level[] = $alpha * ($this->data[$i-1] - $seasonal[$i]) + (1 - $alpha) * ($level[$i-1] + $damping * $trend[$i-1]);
            $trend[] = $beta * ($level[$i] - $level[$i-1]) + (1 - $beta) * $damping * $trend[$i-1];
            $seasonal[] = $gamma_*($this->data[$i-1] - $level[$i-1]) + (1 - $gamma_) * $seasonal[$i];

            $forecast[] = $level[$i] + $this->dampingSum($damping, $i) * $trend[$i] + $seasonal[$i+$hm];
        }

        return $forecast;
    }

    public function getAdditiveMultiplicative(int $future, float $alpha, float $beta, float $gamma, int $cycle, float $damping) : array 
    {
        $level[0] = 1 / $cycle * array_sum($this->data, 0, $cycle);
        $trend[0] = 1 / $cycle;
        $dataLength = count($this->data) + $future;
        $forecast = [];
        $gamma_ = $gamma * (1 - $alpha);

        $sum = 0;
        for($i = 0; $i < $cycle; $i++) {
            $sum += ($this->data[$cycle] - $this->data[$i]) / $cycle;
        }

        $trend[0] *= $sum;

        for($i = 0; $i < $cycle; $i++) {
            $seasonal[$i] = $this->data[$i] / $level[0];
        }

        for($i = 1; $i < $dataLength; $i++) {
            $hm = (int) floor(($i-1) % $cycle) + 1;

            $level[] = $alpha * ($this->data[$i-1] / $seasonal[$i]) + (1 - $alpha) * ($level[$i-1] + $damping * $trend[$i-1]);
            $trend[] = $beta * ($level[$i] - $level[$i-1]) + (1 - $beta) * $damping * $trend[$i-1];
            $seasonal[] = $gamma_*($this->data[$i-1] / ($level[$i-1] + $damping * $trend[$i-1]) + (1 - $gamma_) * $seasonal[$i];

            $forecast[] = ($level[$i] + $this->dampingSum($damping, $i) * $trend[$i-1]) * $seasonal[$i+$hm];
        }

        return $forecast;
    }

    public function getMultiplicativeNone() : array 
    {
        $level[0] = $this->data[0];
        $trend[0] = $this->data[1] / $this->data[0];
    }

    public function getMultiplicativeAdditive() : array 
    {
        $level[0] = $this->data[0];
        $trend[0] = 1 / $cycle;

        $sum = 0;
        for($i = 0; $i < $cycle; $i++) {
            $sum += ($this->data[$cycle] - $this->data[$i]) / $cycle;
        }

        $trend[0] *= $sum;

        for($i = 0; $i < $cycle; $i++) {
            $seasonal[$i] = $data[$i] - $level[0];
        }        
    }

    public function getMultiplicativeMultiplicative() : array 
    {
        $level[0] = $this->data[0];

        $trend[0] = 1 / $cycle;

        $sum = 0;
        for($i = 0; $i < $cycle; $i++) {
            $sum += ($this->data[$cycle] - $this->data[$i]) / $cycle;
        }

        $trend[0] *= $sum;

        for($i = 0; $i < $cycle; $i++) {
            $seasonal[$i] = $this->data[$i] / $level[0];
        }
    }

}