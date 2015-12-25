<?php
namespace phpOMS\Math\Algebra;

class Regression
{
    public static function linearRegression(array $x, array $y)
    {
        $count = count($x);

        if ($count !== count($y)) {
            throw new \Exception('Dimensions');
        }

        $xSum = array_sum($x);
        $ySum = array_sum($y);

        $xxSum = 0;
        $xySum = 0;

        for ($i = 0; $i < $count; $i++) {

            $xySum += ($x[$i] * $y[$i]);
            $xxSum += ($x[$i] * $x[$i]);
        }

        $m = (($count * $xySum) - ($xSum * $ySum)) / (($count * $xxSum) - ($xSum * $xSum));
        $b = ($ySum - ($m * $xSum)) / $count;

        return ['m' => $m, 'b' => $b];
    }
}
