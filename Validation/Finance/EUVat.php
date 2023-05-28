<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Validation\Finance
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Validation\Finance;

use phpOMS\Validation\ValidatorAbstract;

/**
 * Very basic but **incomplete** VAT validation.
 *
 * For a proper VAT validation use existing APIs.
 *
 * @package phpOMS\Validation\Finance
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class EUVat extends ValidatorAbstract
{
    public const PATTERNS = [
        'EUROPE' => '/^(EU)([0-9][9])$/', // This is a special EU VAT.
        'AUT'    => '/^(AT)U[0-9]{8}$/',
        'BEL'    => '/^(BE)[0-9]{10}$/',
        'BGR'    => '/^(BG)[0-9]{9,10}$/',
        'CYP'    => '/^(CY)[0-9]{8}[A-Z]$/',
        'CZE'    => '/^(CZ)([0-9]{8,10}|[0-9]{6}-[0-9]{3,4})$/',
        'DEU'    => '/^(DE)[0-9]{9}$/',
        'DNK'    => '/^(DK)[0-9]{8}$/',
        'EST'    => '/^(EE)[0-9]{9}$/',
        'ESP'    => '/^(ES)([A-Z][0-9]{8}|[A-Z][0-9]{7}[A-Z])$/',
        'FIN'    => '/^(FI)([0-9]{8})$/',
        'FRA'    => '/^(FR)[0-9]{2}[0-9]{9}$/',
        'GBR'    => '/^(GB)([0-9]{9}|[0-9]{12}|(GD)[0-4][0-9]{2}|(HA)5[0-9]{2})$/',
        'GRC'    => '/^(EL)([0-9]{7,9})$/',
        'HRV'    => '/^(HR)([0-9]{11})$/',
        'HUN'    => '/^(HU)([0-9]{8})$/',
        'IRL'    => '/^(IE)([0-9]{7}[A-Z]{1,2}|[0-9][A-Z][0-9]{5}[A-Z])$/',
        'ITA'    => '/^(IT)([0-9]{11})$/',
        'LTU'    => '/^(LT)([0-9]{9}|[0-9]{12})$/',
        'LUX'    => '/^(LU)([0-9]{8})$/',
        'LVA'    => '/^(LV)([0-9]{11})$/',
        'MLT'    => '/^(MT)([0-9]{8})$/',
        'NLD'    => '/^(NL)([0-9]{9}B[0-9]{2})$/',
        'POL'    => '/^(PL)([0-9]{10}|[0-9]{3}-[0-9]{2}-[0-9]{2}-[0-9]{3})$/',
        'PRT'    => '/^(PT)([0-9]{9})$/',
        'ROU'    => '/^(RO)([0-9]{2,10})$/',
        'SWE'    => '/^(SE)([0-9]{10}[0-9]{2})$/i',
        'SVN'    => '/^(SI)([0-9]{8})$/',
        'SVK'    => '/^(SK)([0-9][10])$/',
    ];

    /**
     * {@inheritdoc}
     */
    public static function isValid(mixed $value, array $constraints = null) : bool
    {
        if (!\is_string($value)) {
            return false;
        }

        $value = \str_replace(' ', '', \strtoupper($value));

        foreach (self::PATTERNS as $pattern) {
            if ((bool) \preg_match($pattern, $value)) {
                // VAT potentially valid
                return true;
            } elseif (\stripos($pattern, \substr($value, 0, 2)) !== false) {
                // Regex found but didn't match, therefore VAT is considered potentially invalid
                return false;
            }
        }

        // Couldn't find possible regex, therefore the VAT is considered potentially valid
        return true;
    }
}
