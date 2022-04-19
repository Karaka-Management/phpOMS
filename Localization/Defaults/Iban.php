<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Localization\Defaults
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Localization\Defaults;

/**
 * iban class.
 *
 * @package phpOMS\Localization\Defaults
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class Iban
{
    /**
     * Iban id.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $id = 0;

    /**
     * Iban country.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $country = '';

    /**
     * Iban chars.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $chars = 2;

    /**
     * Iban bban.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $bban = '';

    /**
     * Iban fields.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $fields = '';

    /**
     * Get iban country
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCountry() : string
    {
        return $this->country;
    }

    /**
     * Get iban chars
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getChars() : int
    {
        return $this->chars;
    }

    /**
     * Get iban bban
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getBban() : string
    {
        return $this->bban;
    }

    /**
     * Get iban fields
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getFields() : string
    {
        return $this->fields;
    }
}
