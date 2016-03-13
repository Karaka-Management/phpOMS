<?php

class LinearCongruentialGenerator
{
    public static function bsd(int $seed)
    {
        return function() use(&$seed) {
            return $seed = (1103515245 * $seed + 12345) % (1 << 31);
        }
    }

    public static function msvcrt(int $seed) {
        return function() use (&$seed) {
            return ($seed = (214013 * $seed + 2531011) % (1 << 31)) >> 16;
        };
    }
}