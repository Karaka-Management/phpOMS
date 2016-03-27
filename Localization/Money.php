<?php

namespace phpOMS\Localization;

class Money implements Serialize {

    const MAX_DECIMALS = 5;
    
    private $currency = ISO4217CharEnum::C_USD;
    private $thousands = ',';
    private $decimal = '.';
    
    private $value = 0;
    
    public function __construct(string $currency = ISO4217CharEnum::C_USD, string $thousands = ',', string $decimal = '.') 
    {
        $this->currency = $currency;
        $this->thousands = $thousands;
        $this->decimal = $decimal;
    }
    
    public function setInt(int $value) {
        $this->value = $value;
    }
    
    public function getInt() : int {
        return $this->value;
    }

    public function setString(string $value) {
        $this->value = self::toInt($value, $this->decimal);
    }
    
    public static function toInt(string $value, string $decimal = ',')  : int
    {
        $split = explode($value, $decimal);

        $left = '';
        $left = $split[0];
        $left = str_replace($this->thousands, '', $left);

        $rigth = '';
        if(count($split) > 1) {
            $right = $split[1];
        }
        
        $right = substr($right, 0, -self::MAX_DECIMALS);
        $this->value = (int) $left * 100000 + (int) $right;
    }
    
    public function getAmount(int $decimals = 2) : string 
    {
        if($decimals > ISO4270::{$this->currency}) {
            $decimals = ISO4270::{$this->currency};
        }

        $value = (string) round($value, - self::MAX_DECIMALS + $this->decimals);

        $left = substr($value, 0, -self::MAX_DECIMALS);
        $right = substr($value, -self::MAX_DECIMALS);

        return ($decimals > 0) : number_format($left, 0, $this->thousands, $this->decimal); . $this->decimal . $right : (string) $left;
    }
    
    public function add($value)
    {
        if(is_string($value) || is_float($value)) {
            $this->value += self::toInt((string) $value);
        } elseif(is_int($value)) {
            $this->value += $value;
        } elseif($value instanceof Money) {
            $this->value += $value->getInt();
        }
    }
    
    public function sub($value)
    {
        if(is_string($value) || is_float($value)) {
            $this->value -= self::toInt((string) $value);
        } elseif(is_int($value)) {
            $this->value -= $value;
        } elseif($value instanceof Money) {
            $this->value -= $value->getInt();
        }
    }
    
    public function mult($value)
    {
        if(is_float($value) || is_int($value)) {
            $this->value *= $value;
        }
    }
    
    public function div($value)
    {
        if(is_float($value) || is_int($value)) {
            $this->value = self::toInt((string) ($this->value / $value));
        }
    }

    public function serialize() : int
    {
        return $this->getInt();
    }

    public function unserialize(int $value)
    {
        $this->setInt($value);
    }
}
