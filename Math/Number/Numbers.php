<?php

class Number 
{
    public static function perfect(int $n) : bool
    {
        $sum = 0;

        for($i = 1; $i < $n; $i++) {
            if($n % $i == 0) {
                $sum += $i;
            }
        }

        return $sum == $n;
    }

    public static function selfdescribing(int $n) : bool 
    {
        foreach (str_split($n) as $place => $value) {
            if (substr_count($number, $place) != $value) { 
                return false;
            }
        }
        
        return true;
    }
}