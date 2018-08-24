<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Localization\Defaults
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Localization\Defaults;

/**
 * iban class.
 *
 * @package    phpOMS\Localization\Defaults
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class Iban
{
    /**
     * Iban id.
     *
     * @var int
     * @since 1.0.0
     */
    private $id = 0;

    /**
     * Iban country.
     *
     * @var string
     * @since 1.0.0
     */
    private $country = '';

    /**
     * Iban chars.
     *
     * @var int
     * @since 1.0.0
     */
    private $chars = 2;

    /**
     * Iban bban.
     *
     * @var string
     * @since 1.0.0
     */
    private $bban = '';

    /**
     * Iban fields.
     *
     * @var string
     * @since 1.0.0
     */
    private $fields = '';

    /**
     * Get iban country
     *
     * @return string
     *
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function getFields() : string
    {
        return $this->fields;
    }
}
