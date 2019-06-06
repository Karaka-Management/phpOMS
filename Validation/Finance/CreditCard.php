<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Validation\Finance
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Validation\Finance;

use phpOMS\Validation\ValidatorAbstract;

/**
 * Credit card validation
 *
 * @package    phpOMS\Validation\Finance
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class CreditCard extends ValidatorAbstract
{

    /**
     * {@inheritdoc}
     */
    public static function isValid($value, array $constraints = null) : bool
    {
        if (!\is_string($value)) {
            throw new \InvalidArgumentException();
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
            if ($i % 2 == $parity) {
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
        return ($total % 10 == 0) ? true : false;
    }

    /**
     * Luhn algorithm or mod 10 algorithm is used to verify credit cards.
     *
     * @param string $num credit card number
     *
     * @return bool returns true if the number is a valid credit card and false if it isn't
     *
     * @since  1.0.0
     */
    public static function luhnTest(string $num) : bool
    {
        $len = \strlen($num);
        $sum = 0;

        for ($i = $len - 1; $i >= 0; --$i) {
            $ord = \ord($num[$i]);

            if (($len - 1) & $i) {
                $sum += $ord;
            } else {
                $sum += $ord / 5 + (2 * $ord) % 10;
            }
        }

        return $sum % 10 == 0;
    }
}
