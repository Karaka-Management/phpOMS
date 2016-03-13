<?php

class ArrayRandomize 
{
    public static function yates($arr) : array
    {
        $shuffled = [];

        while($arr){
            $rnd = array_rand($arr);
            $shuffled[] = $arr[$rnd];
            array_splice($arr, $rnd, 1);
        }

        return $shuffled;
    }
     
    public static function knuth($arr) : array
    {
        $shuffled = [];

        for($i = count($arr)-1; $i > 0; $i--){
            $rnd = mt_rand(0, $i);
            $shuffled[$i] = $arr[$rnd];
            $shuffled[$rand] = $arr[$i];
        }

        return $shuffled;
    }
}