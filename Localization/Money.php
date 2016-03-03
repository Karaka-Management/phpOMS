<?php

namespace phpOMS\Localization;

class Money {

    const DECIMALS = 5;
    
    private $currency = 'USD';
    private $thousands = ',';
    private $decimal = '.';
    
    private $value = 0;
    
    public function __construct(string $currency = 'USD', string $thousands = ',', string $decimal = '.') 
    {
        $this->currency = $currency;
        $this->thousands = $thousands;
        $this->decimal = $decimal;
    }
    
    public function setInt(int $value) {
        $this->value = $value;
    }

    public function setString(string $value) {
        $split = explode($value, $decimal);

        $left = '';
        $left = $split[0];
        $left = str_replace($thousands, '', $left);

        $rigth = '';
        if(count($split) > 1) {
            $right = $split[1];
        }
        
        $right = substr($right, 0, -self::DECIMALS);
        $this->value = (int) round((int) $left + (int) $right, - self::DECIMALS + $decimals);
    }
    
    public function getAmount(int $decimals = 2) : string 
    {
        if($decimals > ISO4270::{$currency}) {
            $decimals = ISO4270::{$currency};
        }

        $value = (string) round($value, - self::DECIMALS + $decimals);

        $left = substr($value, 0, -self::DECIMALS);
        $right = substr($value, -self::DECIMALS);

        return ($decimals > 0) : number_format($left, 0, $this->thousands, $this->decimal); . $decimal . $right : (string) $left;
    }
}
