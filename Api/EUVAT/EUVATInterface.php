<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Api\EUVAT
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Api\EUVAT;

/**
 * EU VAT validation interface
 *
 * @package phpOMS\Api\EUVAT
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface EUVATInterface
{
    /**
     * Validate VAT
     *
     * @param string $otherVAT EU VAT to validate (VAT in own country not possible)
     * @param string $ownVAT   Valid own EU VAT
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function validate(string $otherVAT, string $ownVAT = '') : array;

    /**
     * Qualified VAT validation
     *
     * The qualified validation is very strict, even one wrong character will result in a failure (e.g. street abbreviations)
     *
     * @param string $otherVAT    EU VAT to validate (VAT in own country not possible)
     * @param string $ownVAT      Valid own EU VAT
     * @param string $otherName   Company name to validate
     * @param string $otherCity   City to validate
     * @param string $otherPostal Zip to validate
     * @param string $otherStreet Street to validate
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function validateQualified(string $otherVAT, string $ownVAT, string $otherName, string $otherCity, string $otherPostal, string $otherStreet) : array;
}
