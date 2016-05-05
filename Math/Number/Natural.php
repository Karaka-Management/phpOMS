<?php

class Natural
{
    public static function isNatural($value) : bool
    {
        return is_int($value) && $value >= 0;
    }
}