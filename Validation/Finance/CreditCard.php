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
 * Credit card validation
 *
 * @package phpOMS\Validation\Finance
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class CreditCard extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public static function isValid($value, array $constraints = null) : bool
    {
        if (!\is_string($value)) {
            return false;
        }

        $value = \preg_replace('/\D/', '', $value) ?? '';

        // Set the string length and parity
        $numberLength = \strlen($value);
        $parity       = $numberLength % 2;

        // Loop through each digit and do the maths
        $total = 0;
        for ($i = 0; $i < $numberLength; ++$i) {
            $digit = (int) $value[$i];
            // Multiply alternate digits by two
            if ($i % 2 === $parity) {
                $digit *= 2;
                // If the sum is two digits, add them together (in effect)
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            // Total up the digits
            $total += $digit;
        }

        // If the total mod 10 equals 0, the value is valid
        return $total % 10 === 0;
    }
}
