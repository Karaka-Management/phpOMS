<?php
namespace phpOMS\Math\Statistic;

use phpOMS\Math\Functions;

class BinomialDistribution
{
    public static function getDensity(\int $n, \int $k, \float $p)
    {
        $max = max([$k, $n - $k]);
        $min = min([$k, $n - $k]);

        return Functions::fact($n, $max + 1) / Functions::fact($min) * pow($p, $k) * pow(1 - $p, $n - $k);
    }

    public function getDistribution(\int $n, \int $x, \float $p)
    {
        $sum = 0.0;

        for($i = 0; $i < $x; $i++) {
            $sum += self::getDensity($n, $i, $p);
        }

        return $sum;
    }

    public function getExpectedValue(\int $n, \float $p)
    {
        return $n * $p;
    }

    public function getVariance(\int $n, \float $p)
    {
        return $n * $p * (1 - $p);
    }
}
