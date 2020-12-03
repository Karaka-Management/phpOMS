<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Localization
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Localization;

/**
 * Money class.
 *
 * @package phpOMS\Localization
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Money implements \Serializable
{
    /**
     * Max amount of decimals.
     *
     * @var int
     * @since 1.0.0
     */
    public const MAX_DECIMALS = 4;

    /**
     * Thousands separator.
     *
     * @var string
     * @since 1.0.0
     */
    private string $thousands = ',';

    /**
     * Decimal separator.
     *
     * @var string
     * @since 1.0.0
     */
    private string $decimal = '.';

    /**
     * Currency symbol position
     *
     * @var int
     * @since 1.0.0
     */
    private int $position = 1;

    /**
     * Currency symbol.
     *
     * @var string
     * @since 1.0.0
     */
    private string $symbol = ISO4217SymbolEnum::_USD;

    /**
     * Value.
     *
     * @var int
     * @since 1.0.0
     */
    private int $value = 0;

    /**
     * Constructor.
     *
     * @param int|float|string $value     Value
     * @param string           $thousands Thousands separator
     * @param string           $decimal   Decimal separator
     * @param string           $symbol    Currency symbol
     * @param int              $position  Symbol position
     *
     * @since 1.0.0
     */
    public function __construct(int|float|string $value = 0, string $thousands = ',', string $decimal = '.', string $symbol = '', int $position = 0)
    {
        $this->value     = \is_int($value) ? $value : self::toInt((string) $value);
        $this->thousands = $thousands;
        $this->decimal   = $decimal;
        $this->symbol    = $symbol;
        $this->position  = $position;
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
     * @throws \Exception this exception is thrown if an internal explode or substr error occurs
     *
     * @since 1.0.0
     */
    public static function toInt(string $value, string $thousands = ',', string $decimal = '.') : int
    {
        $split = \explode($decimal, $value);

        if ($split === false) {
            throw new \Exception('Internal explode error.'); // @codeCoverageIgnore
        }

        $left  = $split[0];
        $left  = \str_replace($thousands, '', $left);
        $right = '';

        if (\count($split) > 1) {
            $right = $split[1];
        }

        $right = \substr($right, 0, self::MAX_DECIMALS);
        if ($right === false) {
            throw new \Exception('Internal substr error.'); // @codeCoverageIgnore
        }

        return ((int) $left) * 10 ** self::MAX_DECIMALS + (int) \str_pad($right, self::MAX_DECIMALS, '0');
    }

    /**
     * Set localization.
     *
     * @param string $thousands Thousands separator
     * @param string $decimal   Decimal separator
     * @param string $symbol    Currency symbol
     * @param int    $position  Symbol position
     *
     * @return Money
     *
     * @since 1.0.0
     */
    public function setLocalization(string $thousands = ',', string $decimal = '.', string $symbol = '', int $position = 0) : self
    {
        $this->thousands = $thousands;
        $this->decimal   = $decimal;
        $this->symbol    = $symbol;
        $this->position  = $position;

        return $this;
    }

    /**
     * Set value by string.
     *
     * @param string $value Money value
     *
     * @return Money
     *
     * @since 1.0.0
     */
    public function setString(string $value) : self
    {
        $this->value = self::toInt($value, $this->thousands, $this->decimal);

        return $this;
    }

    /**
     * Get money.
     *
     * @param int $decimals Precision
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCurrency(int $decimals = 2) : string
    {
        return ($this->position === 0 && !empty($this->symbol) ? $this->symbol . ' ' : '') . $this->getAmount($decimals) . ($this->position === 1 ? ' ' . $this->symbol : '');
    }

    /**
     * Get money.
     *
     * @param int $decimals Precision
     *
     * @return string
     *
     * @throws \Exception this exception is thrown if an internal substr error occurs
     *
     * @since 1.0.0
     */
    public function getAmount(int $decimals = 2) : string
    {
        $value = $this->value === 0
            ? \str_repeat('0', self::MAX_DECIMALS)
            : (string) \round($this->value, -self::MAX_DECIMALS + $decimals);

        $left = \substr($value, 0, -self::MAX_DECIMALS);

        /** @var string $left */
        $left  = $left === false ? '0' : $left;
        $right = \substr($value, -self::MAX_DECIMALS);

        if ($right === false) {
            throw new \Exception(); // @codeCoverageIgnore
        }

        return $decimals > 0
            ? \number_format((float) $left, 0, $this->decimal, $this->thousands) . $this->decimal . \substr($right, 0, $decimals)
            : \str_pad($left, 1, '0');
    }

    /**
     * Add money.
     *
     * @param int|float|string|Money $value Value to add
     *
     * @return Money
     *
     * @since 1.0.0
     */
    public function add(int|float|string|self $value) : self
    {
        if (\is_string($value) || \is_float($value)) {
            $this->value += self::toInt((string) $value, $this->thousands, $this->decimal);
        } elseif (\is_int($value)) {
            $this->value += $value;
        } else {
            $this->value += $value->getInt();
        }

        return $this;
    }

    /**
     * Get money value.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getInt() : int
    {
        return $this->value;
    }

    /**
     * Sub money.
     *
     * @param int|float|string|Money $value Value to subtract
     *
     * @return Money
     *
     * @since 1.0.0
     */
    public function sub(int|float|string|self $value) : self
    {
        if (\is_string($value) || \is_float($value)) {
            $this->value -= self::toInt((string) $value, $this->thousands, $this->decimal);
        } elseif (\is_int($value)) {
            $this->value -= $value;
        } else {
            $this->value -= $value->getInt();
        }

        return $this;
    }

    /**
     * Mult.
     *
     * @param int|float $value Value to multiply with
     *
     * @return Money
     *
     * @since 1.0.0
     */
    public function mult(int|float $value) : self
    {
        $this->value = (int) ($this->value * $value);

        return $this;
    }

    /**
     * Div.
     *
     * @param int|float $value Value to divide by
     *
     * @return Money
     *
     * @since 1.0.0
     */
    public function div(int|float $value) : self
    {
        $this->value = (int) ($this->value / $value);

        return $this;
    }

    /**
     * Abs.
     *
     * @return Money
     *
     * @since 1.0.0
     */
    public function abs() : self
    {
        $this->value = \abs($this->value);

        return $this;
    }

    /**
     * Power.
     *
     * @param int|float $value Value to power
     *
     * @return Money
     *
     * @since 1.0.0
     */
    public function pow(int|float $value) : self
    {
        $this->value = (int) ($this->value ** $value);

        return $this;
    }

    /**
     * Searialze.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function serialize() : string
    {
        return (string) $this->getInt();
    }

    /**
     * Unserialize.
     *
     * @param mixed $value Value to unserialize
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function unserialize($value) : void
    {
        $this->setInt((int) $value);
    }

    /**
     * Set money value.
     *
     * @param int $value Value
     *
     * @return Money
     *
     * @since 1.0.0
     */
    public function setInt(int $value) : self
    {
        $this->value = $value;

        return $this;
    }
}
