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
    const MAX_DECIMALS = 4;

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
     * @param string|int|float $value     Value
     * @param string $thousands Thousands separator
     * @param string $decimal   Decimal separator
     * @param string $symbol    Currency symbol
     * @param int $position     Symbol position
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct($value = 0, string $thousands = ',', string $decimal = '.', string $symbol = '', int $position = 0)
    {
        $this->value     = is_int($value) ? $value : self::toInt((string) $value);
        $this->thousands = $thousands;
        $this->decimal   = $decimal;
        $this->symbol    = $symbol;
        $this->position  = $position;
    }

    /**
     * Set localization.
     *
     * @param string $thousands Thousands separator
     * @param string $decimal   Decimal separator
     * @param string $symbol    Currency symbol
     * @param int $position     Symbol position
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setLocalization(string $thousands = ',', string $decimal = '.', string $symbol = '', int $position = 0)
    {
        $this->thousands = $thousands;
        $this->decimal   = $decimal;
        $this->symbol    = $symbol;
        $this->position  = $position;
    }

    /**
     * Set value by string.
     *
     * @param string $value Money value
     *
     * @return Money
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setString(string $value) : Money
    {
        $this->value = self::toInt($value, $this->thousands, $this->decimal);

        return $this;
    }

    /**
     * Money to int.
     *
     * @param string $value     Money value
     * @param string $thousands Thousands character
     * @param string $decimal   Decimal character
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function toInt(string $value, string $thousands = ',', string $decimal = '.')  : int
    {
        $split = explode($decimal, $value);
        $left  = $split[0];
        $left  = str_replace($thousands, '', $left);
        $right = '';

        if (count($split) > 1) {
            $right = $split[1];
        }

        $right = substr($right, 0, self::MAX_DECIMALS);

        return (int) (((int) $left) * 10 ** self::MAX_DECIMALS + (int) str_pad($right, self::MAX_DECIMALS, '0'));
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
        $value = (string) round($this->value, -self::MAX_DECIMALS + $decimals);

        $left  = substr($value, 0, -self::MAX_DECIMALS);
        $right = substr($value, -self::MAX_DECIMALS);

        return ($decimals > 0) ? number_format($left, 0, $this->decimal, $this->thousands) . $this->decimal . substr($right, 0, $decimals) : (string) $left;
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
    public function getCurrency(int $decimals = 2) : string
    {
        return ($position === 0 ? $smbol : '') . $this->getAmount($decimals, $thousands, $decimal) . ($position === 1 ? $smbol : '');
    }

    /**
     * Add money.
     *
     * @param Money|string|int|float $value
     *
     * @return Money
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function add($value) : Money
    {
        if (is_string($value) || is_float($value)) {
            $this->value += self::toInt((string) $value, $this->thousands, $this->decimal);
        } elseif (is_int($value)) {
            $this->value += $value;
        } elseif ($value instanceof Money) {
            $this->value += $value->getInt();
        }

        return $this;
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
     * Sub money.
     *
     * @param Money|string|int|float $value
     *
     * @return Money
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function sub($value) : Money
    {
        if (is_string($value) || is_float($value)) {
            $this->value -= self::toInt((string) $value, $this->thousands, $this->decimal);
        } elseif (is_int($value)) {
            $this->value -= $value;
        } elseif ($value instanceof Money) {
            $this->value -= $value->getInt();
        }

        return $this;
    }

    /**
     * Mult.
     *
     * @param int|float $value
     *
     * @return Money
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function mult($value) : Money
    {
        if (is_float($value) || is_int($value)) {
            $this->value = (int) ($this->value * $value);
        }

        return $this;
    }

    /**
     * Div.
     *
     * @param int|float $value
     *
     * @return Money
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function div($value) : Money
    {
        if (is_float($value) || is_int($value)) {
            $this->value = (int) ($this->value / $value);
        }

        return $this;
    }

    /**
     * Abs.
     *
     * @return Money
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function abs() : Money
    {
        $this->value = abs($this->value);

        return $this;
    }

    /**
     * Power.
     *
     * @param int|float $value
     *
     * @return Money
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function pow($value) : Money
    {
        if (is_float($value) || is_int($value)) {
            $this->value = $this->value ** $value;
        }

        return $this;
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

    /**
     * Set money value.
     *
     * @param int $value Value
     *
     * @return Money
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setInt(int $value) : Money
    {
        $this->value = $value;

        return $this;
    }
}
