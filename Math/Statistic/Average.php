<?php
namespace phpOMS\Math\Statistic;

class Average
{

    public static function weightedAverage(array $value, array $weight)
    {
        if (($count = count($value)) !== count($weight)) {
            throw new \Exception('Dimension');
        }

        $avg = 0.0;

        for ($i = 0; $i < $count; $i++) {
            $avg += $value[$i] * $weight[$i];
        }

        return $avg;
    }

    public static function mode($values)
    {
        $count = array_count_values($values);
        $best  = max($count);

        return array_keys($count, $best);
    }

    public static function median(array $values)
    {
        sort($values);
        $count     = count($values);
        $middleval = (int) floor(($count - 1) / 2);

        if ($count % 2) {
            $median = $values[$middleval];
        } else {
            $low    = $values[$middleval];
            $high   = $values[$middleval + 1];
            $median = (($low + $high) / 2);
        }

        return $median;
    }

    public static function arithmeticMean(array $values, \int $offset = 0)
    {
        sort($values);

        if ($offset > 0) {
            $values = array_slice($values, $offset, -$offset);
        }

        $count = count($values);

        if ($count === 0) {
            throw new \Exception('Division zero');
        }

        return array_sum($values) / $count;
    }

    public static function geometricMean(array $values)
    {
        $count = count($values);

        if ($count === 0) {
            throw new \Exception('Division zero');
        }

        return pow(array_product($values), 1 / $count);
    }

    public static function harmonicMean(array $values)
    {
        $count = count($values);
        $sum   = 0.0;

        foreach ($values as $value) {
            if ($value === 0) {
                throw new \Exception('Division zero');
            }

            $sum += 1 / $value;
        }

        return 1 / ($sum / $count);
    }

    public static function angleMean($angles)
    {
        $y    = $x = 0;
        $size = count($angles);

        for ($i = 0; $i < $size; $i++) {
            $x += cos(deg2rad($angles[$i]));
            $y += sin(deg2rad($angles[$i]));
        }

        $x /= $size;
        $y /= $size;

        return rad2deg(atan2($y, $x));
    }

    public static function timeToAngle(\string $time)
    {
        $parts = explode(':', $time);

        if (count($parts) !== 3) {
            throw new \Exception('Wrong time format');
        }

        $sec   = ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2];
        $angle = 360.0 * ($sec / 86400.0);

        return $angle;
    }

    public static function angleToTime(\float $angle)
    {
        $sec   = 86400.0 * $angle / 360.0;
        $parts = [floor($sec / 3600), floor(($sec % 3600) / 60), $sec % 60];
        $time  = sprintf('%02d:%02d:%02d', $parts[0], $parts[1], $parts[2]);

        return $time;
    }

    public static function angleMean2(array $angle)
    {
        $sins = 0.0;
        $coss = 0.0;

        foreach ($angle as $a) {
            $sins += sin(deg2rad($a));
            $coss += cos(deg2rad($a));
        }

        $avgsin = $sins / (0.0 + count($angle));
        $avgcos = $coss / (0.0 + count($angle));
        $avgang = rad2deg(atan2($avgsin, $avgcos));

        while ($avgang < 0.0) $avgang += 360.0;

        return $avgang;
    }
}
