<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Localization;

/**
 * Money class.
 *
 * @category   Framework
 * @package    phpOMS\Localization
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Money implements \Serializable 
{

    /**
     * Max amount of decimals.
     *
     * @var int
     * @since 1.0.0
     */
    const MAX_DECIMALS = 5;
    
    /**
     * Currency symbol.
     *
     * @var string
     * @since 1.0.0
     */
    private $currency = ISO4217CharEnum::C_USD;

    /**
     * Thousands separator.
     *
     * @var string
     * @since 1.0.0
     */
    private $thousands = ',';

    /**
     * Decimal separator.
     *
     * @var string
     * @since 1.0.0
     */
    private $decimal = '.';
    
    /**
     * Value.
     *
     * @var int
     * @since 1.0.0
     */
    private $value = 0;
    
    /**
     * Constructor.
     *
     * @param string $currency Currency symbol
     * @param string $thousands Thousands separator
     * @param string $decimal Decimal separator
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(string $currency = ISO4217CharEnum::C_USD, string $thousands = ',', string $decimal = '.') 
    {
        $this->currency = $currency;
        $this->thousands = $thousands;
        $this->decimal = $decimal;
    }
    
    /**
     * Set money value.
     *
     * @param int $value Value
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setInt(int $value) 
    {
        $this->value = $value;
    }
    
    /**
     * Get money value.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getInt() : int 
    {
        return $this->value;
    }

    /**
     * Set value by string.
     *
     * @param string $value Money value
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setString(string $value) 
    {
        $this->value = self::toInt($value, $this->decimal);
    }
    
    /**
     * Money to int.
     *
     * @param string $value Money value
     * @param string $decimal Decimal character
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
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
    
    /**
     * Get money.
     *
     * @param int $decimals Precision
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getAmount(int $decimals = 2) : string 
    {
        if($decimals > ($dec = ISO4217DecimalEnum::${'C_' . strtoupper($this->currency)})) {
            $decimals = $dec ;
        }

        $value = (string) round($value, - self::MAX_DECIMALS + $this->decimals);

        $left = substr($value, 0, -self::MAX_DECIMALS);
        $right = substr($value, -self::MAX_DECIMALS);

        return ($decimals > 0) : number_format($left, 0, $this->thousands, $this->decimal); . $this->decimal . $right : (string) $left;
    }
    
    /**
     * Add money.
     *
     * @param Money|string|int|float $value
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
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
    
    /**
     * Sub money.
     *
     * @param Money|string|int|float $value
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
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
    
    /**
     * Mult.
     *
     * @param int|float $value
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function mult($value)
    {
        if(is_float($value) || is_int($value)) {
            $this->value *= $value;
        }
    }
    
    /**
     * Div.
     *
     * @param int|float $value
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function div($value)
    {
        if(is_float($value) || is_int($value)) {
            $this->value = self::toInt((string) ($this->value / $value));
        }
    }

    /**
     * Searialze.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function serialize()
    {
        return $this->getInt();
    }

    /**
     * Unserialize.
     *
     * @param mixed $value
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function unserialize($value)
    {
        $this->setInt($value);
    }
}
