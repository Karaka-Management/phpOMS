<?php
namespace phpOMS\Math;

class Functions
{
    public static function getGammaInteger(\int $k)
    {
        return self::fact($k-1);
    }

    public static function fact(\int $n, \int $start = 1)
    {
        $fact = 1;

        for($i = $start; $i < $n; $i++) {
            $fact *= $i;
        }

        return $fact;
    }
}
