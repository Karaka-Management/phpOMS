<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Localization
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
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
 * @license OMS License 1.0
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
    private int $position = 1;

    /**
     * Currency symbol.
     *
     * @var string
     * @since 1.0.0
     */
    private string $symbol = ISO4217SymbolEnum::_USD;

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
    public function __construct(int | float | string $value = 0, string $thousands = ',', string $decimal = '.', string $symbol = '', int $position = 0)
    {
        $this->symbol   = $symbol;
        $this->position = $position;

        parent::__construct($value, $thousands, $decimal);
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
    public function getCurrency(?int $decimals = 2) : string
    {
        return ($this->position === 0 && !empty($this->symbol) ? $this->symbol . ' ' : '') . $this->getAmount($decimals) . ($this->position === 1 ? ' ' . $this->symbol : '');
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
}
