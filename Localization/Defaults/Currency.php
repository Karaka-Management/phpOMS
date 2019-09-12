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
     * @var   int
     * @since 1.0.0
     */
    private $id = 0;

    /**
     * Currency name.
     *
     * @var   string
     * @since 1.0.0
     */
    private $name = '';

    /**
     * Currency code.
     *
     * @var   string
     * @since 1.0.0
     */
    private $code = '';

    /**
     * Currency code.
     *
     * @var   int
     * @since 1.0.0
     */
    private $number = 0;

    /**
     * Currency decimals.
     *
     * @var   int
     * @since 1.0.0
     */
    private $decimals = 0;

    /**
     * Currency countries.
     *
     * @var   string
     * @since 1.0.0
     */
    private $countries = '';

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
     * Get currency code
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getNumber() : int
    {
        return $this->number;
    }

    /**
     * Get currency decimals
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getDecimals() : int
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
