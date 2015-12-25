<?php
namespace phpOMS\Math\Statistic;

class Basic
{
    public static function freaquency(array $values) : \float
    {
        $freaquency = [];

        if (!($isArray = is_array(reset($values)))) {
            $sum = array_sum($values);
        }

        foreach ($values as $value) {
            if ($isArray) {
                $freaquency[] = self::freaquency($value);
            } else {
                $freaquency[] = $value / $sum;
            }
        }

        return $freaquency;
    }
}
