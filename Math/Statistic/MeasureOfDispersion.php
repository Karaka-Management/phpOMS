<?php
namespace phpOMS\Math\Statistic;

class MeasureOfDispersion
{
    public static function range($values)
    {
        $end   = end($values);
        $start = reset($values);

        return $start - $end;
    }

    public static function empiricalVariance($values)
    {
        $count = count($values);

        if ($count === 0) {
            throw new \Exception('Division zero');
        }

        $mean = Average::arithmeticMean($values);
        $sum  = 0;

        foreach ($values as $value) {
            $sum += $value - $mean;
        }

        return $sum / $count;
    }

    public static function sampleVariance($values)
    {
        $count = count($values);

        if ($count < 2) {
            throw new \Exception('Division zero');
        }

        return $count * self::empiricalVariance($values) / ($count - 1);
    }

    public static function standardDeviation($values)
    {
        return sqrt(self::sampleVariance($values));
    }

    public static function empiricalVariationcofficient($values)
    {
        $mean = Average::arithmeticMean($values);

        if ($mean === 0) {
            throw new \Exception('Division zero');
        }

        return self::standardDeviation($values) / $mean;
    }

    public static function empiricalCovariance($x, $y)
    {
        $count = count($x);

        if ($count < 2) {
            throw new \Exception('Division zero');
        }

        if ($count !== count($y)) {
            throw new \Exception('Dimensions');
        }

        $xMean = Average::arithmeticMean($x);
        $yMean = Average::arithmeticMean($y);

        $sum = 0.0;

        for ($i = 0; $i < $count; $i++) {
            $sum += ($x[$i] - $xMean) * ($y[$i] - $yMean);
        }

        return $sum / ($count - 1);
    }

    public static function bravaisPersonCorrelationcoefficient($x, $y)
    {
        return self::empiricalCovariance($x, $y) / sqrt(self::empiricalCovariance($x, $x) * self::empiricalCovariance($y, $y));
    }
}
