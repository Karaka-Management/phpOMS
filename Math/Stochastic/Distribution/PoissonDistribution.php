<?php
namespace phpOMS\Math\Statistic;

use phpOMS\Math\Functions;

class PoissonDistribution
{
    public static function getPoisson(\int $k, \float $lambda)
    {
        return exp($k * log($lambda) - $lambda - log(Functions::getGammaInteger($k + 1)));
    }
}
