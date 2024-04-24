<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Localization\Defaults
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Localization\Defaults;

/**
 * iban class.
 *
 * @package phpOMS\Localization\Defaults
 * @license OMS License 2.0
 * @link    https://jingga.app
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
    public int $id = 0;

    /**
     * Iban country.
     *
     * @var string
     * @since 1.0.0
     */
    public string $country = '';

    /**
     * Iban chars.
     *
     * @var int
     * @since 1.0.0
     */
    public int $chars = 2;

    /**
     * Iban bban.
     *
     * @var string
     * @since 1.0.0
     */
    public string $bban = '';

    /**
     * Iban fields.
     *
     * @var string
     * @since 1.0.0
     */
    public string $fields = '';

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
