<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\RnG
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\RnG;

/**
 * Phone generator.
 *
 * @package phpOMS\Utils\RnG
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Phone
{
    /**
     * Get a random phone number.
     *
     * @param bool              $isInt     This number uses a country code
     * @param string            $struct    Number layout
     * @param array<null|array> $size      Digits per placeholder [min, max]
     * @param null|array        $countries Country codes
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function generatePhone(
        bool $isInt = true,
        string $struct = '+$1 ($2) $3-$4',
        array $size = [null, [3, 4], [3, 5], [3, 8],],
        array $countries = null
    ) : string
    {
        $numberString = $struct;

        if ($isInt) {
            if ($countries === null) {
                $countries = ['de' => 49, 'us' => 1];
            }

            $numberString = \str_replace(
                '$1',
                (string) $countries[\array_keys($countries)[\mt_rand(0, \count($countries) - 1)]],
                $numberString
            );
        }

        $numberParts = \substr_count($struct, '$');

        for ($i = ($isInt ? 2 : 1); $i <= $numberParts; ++$i) {
            $numberString = \str_replace(
                '$' . $i,
                StringUtils::generateString(
                    $size[$i - 1][0] ?? 0,
                    $size[$i - 1][1] ?? 0,
                    '0123456789'
                ),
                $numberString
            );
        }

        return $numberString;
    }
}
