<?php

namespace phpOMS\Math\Finance\Forecasting;

use phpOMS\Math\Statistic\Average;

class ClassicalDecomposition
{
    const ADDITIVE = 0;
    const MULTIPLICATIVE = 1;

    private $mode = self::ADDITIVE;
    private $data = [];
    private $order = 0;
    private $dataSize = 0;

    public function __construct(array $data, int $order, int $mode = self::ADDITIVE)
    {
        $this->mode  = $mode;
        $this->data  = $data;
        $this->order = $order;

        $this->dataSize = count($data);
    }

    public function getDecomposition() : array
    {
        $trendCycleComponent = self::computeTrendCycle($this->data, $this->order);
        $detrendedSeries     = self::computeDetrendedSeries($this->data, $trendCycleComponent, $this->mode);
        $seasonalComponent   = $this->computeSeasonalComponent($detrendedSeries, $this->order);
        $remainderComponent  = $this->computeRemainderComponent($trendCycleComponent, $seasonalComponent);

        return [
            'trendCycleComponent' => $trendCycleComponent,
            'detrendedSeries'     => $detrendedSeries,
            'seasonalComponent'   => $seasonalComponent,
            'remainderComponent'  => $remainderComponent,
        ];
    }

    public static function computeTrendCycle(array $data, int $order) : array
    {
        $mMA = Average::totalMovingAverage($data, $order, null, true);

        return $order % 2 === 0 ? Average::totalMovingAverage($mMA, 2, null, true) : $mMA;
    }

    public static function computeDetrendedSeries(array $data, array $trendCycleComponent, int $mode) : array
    {
        $detrended = [];
        $count     = count($trendCycleComponent);
        $start     = self::getStartOfDecomposition(count($data), $count);

        for ($i = 0; $i < $count; $i++) {
            $detrended[] = $mode === self::ADDITIVE ? $data[$start + $i] - $trendCycleComponent[$i] : $data[$start + $i] / $trendCycleComponent[$i];
        }

        return $detrended;
    }

    /**
     * Moving average can't start at index 0 since it needs to go m indices back for average -> can only start at m
     */
    public static function getStartOfDecomposition(int $dataSize, int $trendCycleComponents) : int
    {
        return ($dataSize - $trendCycleComponents) / 2;
    }

    private function computeSeasonalComponent() : array
    {
        $seasonalComponent = [];

        for ($i = 0; $i < $this->orderSize; $i++) {
            $temp = [];

            for ($j = $i * $this->order; $j < $count; $j += $this->order) {
                $temp[] = $this->data[$j];
            }

            $seasonalComponent[] = Average::arithmeticMean($temp);
        }

        return $seasonalComponent;
    }

    public static function computeRemainderComponent(array $trendCycleComponent, array $seasonalComponent) : array
    {
        $remainderComponent = [];
        $count              = count($trendCycleComponent);
        $start              = self::getStartOfDecomposition($this->dataSize, $count);
        $seasons            = count($seasonalComponent);

        for ($i = 0; $i < $count; $i++) {
            $remainderComponent[] = $this->mode === self::ADDITIVE ? $this->data[$start + $i] - $trendCycleComponent[$i] - $seasonalComponent[$i % $seasons] : $this->data[$start + $i] / ($trendCycleComponent[$i] * $seasonalComponent[$i % $seasons]);
        }

        return $remainderComponent;
    }
}