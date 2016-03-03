<?php

namespace phpOMS\Localization;

class Money {

    const DECIMALS = 5;

    private static function getFromInt(int $value, string $currency = 'USD', string $thousands = ',', string $decimal = '.', int $decimals = 2) : string
    {
        if($decimals > ISO4270::{$currency}) {
            $decimals = ISO4270::{$currency};
        }

        $value = (string) round($value, - self::DECIMALS + $decimals);

        $left = substr($value, 0, -self::DECIMALS);
        $right = substr($value, -self::DECIMALS);

        return ($decimals > 0) : number_format($left, 0, $thousands, $decimal); . $decimal . $right : (string) $left;
    }

    private static function getFromString(string $value, string $currency = 'USD', string $thousands = ',', string $decimal = '.', int $decimals = 2) : int
    {
        if($decimals > ISO4270::{$currency}) {
            $decimals = ISO4270::{$currency};
        }

        $split = explode($value, $decimal);

        $left = '';
        $left = $split[0];
        $left = str_replace($thousands, '', $left);

        $rigth = '';
        if(count($split) > 1) {
            $right = $split[1];
        }
        
        $right = substr($right, 0, -self::DECIMALS);
        $value = (int) round((int) $left + (int) $right, - self::DECIMALS + $decimals);
        
        return $value;
    }
}
