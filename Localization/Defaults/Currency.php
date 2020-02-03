<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Localization\Defaults
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Localization\Defaults;

/**
 * Currency class.
 *
 * @package phpOMS\Localization\Defaults
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Currency
{
    /**
     * Currency id.
     *
     * @var int
     * @since 1.0.0
     */
    private int $id = 0;

    /**
     * Currency name.
     *
     * @var string
     * @since 1.0.0
     */
    private string $name = '';

    /**
     * Currency code.
     *
     * @var string
     * @since 1.0.0
     */
    private string $code = '';

    /**
     * Currency symbol.
     *
     * @var string
     * @since 1.0.0
     */
    private string $symbol = '';

    /**
     * Currency number.
     *
     * @var string
     * @since 1.0.0
     */
    private string $number = 0;

    /**
     * Currency subunits.
     *
     * @var int
     * @since 1.0.0
     */
    private int $subunits = 0;

    /**
     * Currency decimals.
     *
     * @var string
     * @since 1.0.0
     */
    private string $decimals = 0;

    /**
     * Currency countries.
     *
     * @var string
     * @since 1.0.0
     */
    private string $countries = '';

    /**
     * Get currency name
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Get currency code
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCode() : string
    {
        return $this->code;
    }

    /**
     * Get currency symbol
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getSymbol() : string
    {
        return $this->symbol;
    }

    /**
     * Get currency number
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getNumber() : string
    {
        return $this->number;
    }

    /**
     * Get currency subunits
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getSubunits() : int
    {
        return $this->subunits;
    }

    /**
     * Get currency decimals
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getDecimals() : string
    {
        return $this->decimals;
    }

    /**
     * Get currency countries
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCountries() : string
    {
        return $this->countries;
    }
}
