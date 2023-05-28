<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Localization
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Localization;

use phpOMS\Stdlib\Base\FloatInt;

/**
 * Money class.
 *
 * @package phpOMS\Localization
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Money extends FloatInt
{
    /**
     * Currency symbol position
     *
     * @var int
     * @since 1.0.0
     */
    private int $position = 0;

    /**
     * Currency symbol.
     *
     * @var string
     * @since 1.0.0
     */
    private string $symbol = ISO4217SymbolEnum::_USD;

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
        $this->symbol   = $symbol;
        $this->position = $position;

        parent::setLocalization($thousands, $decimal);

        return $this;
    }

    /**
     * Get money.
     *
     * @param int $decimals Precision (null = auto decimals)
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCurrency(?int $decimals = 2, int $position = null, string $symbol = null) : string
    {
        return (($position ?? $this->position) === 0 && !empty($symbol ?? $this->symbol) ? ($symbol ?? $this->symbol) . ' ' : '' )
            . $this->getAmount($decimals)
            . (($position ?? $this->position) === 1 && !empty($symbol ?? $this->symbol) ? ' ' . ($symbol ?? $this->symbol) : '');
    }

    /**
     * Get currency symbol
     *
     * @return string
     * @since 1.0.0
     */
    public function getCurrencySymbol() : string
    {
        return $this->symbol;
    }

    /**
     * Create Money from FloatInt.
     *
     * @param FloatInt $value FloatInt value
     *
     * @return self
     */
    public static function fromFloatInt(FloatInt $value) : self
    {
        $money        = new self();
        $money->value = $value->value;

        return $money;
    }
}
