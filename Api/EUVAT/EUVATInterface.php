<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Api\EUVAT
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Api\EUVAT;

interface EUVATInterface
{
    /**
     * Validate VAT
     *
     * @param string $ownVAT   Valid own EU VAT
     * @param string $otherVAT EU VAT to validate (VAT in own country not possible)
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function validate(string $ownVAT, string $otherVAT) : int;

    /**
     * Qualified VAT validation
     *
     * The qualified validation is very strict, even one wrong character will result in a failure (e.g. street abbreviations)
     *
     * @param string $ownVAT      Valid own EU VAT
     * @param string $otherVAT    EU VAT to validate (VAT in own country not possible)
     * @param string $otherName   Company name to validate
     * @param string $otherCity   City to validate
     * @param string $otherPostal Zip to validate
     * @param string $otherStreet Street to validate
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function validateQualified(string $ownVAT, string $otherVAT, string $otherName, string $otherCity, string $otherPostal, string $otherStreet) : array;
}
