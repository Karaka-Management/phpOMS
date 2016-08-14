<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Validation\Base;

use phpOMS\Validation\ValidatorAbstract;

/**
 * Validator abstract.
 *
 * @category   Validation
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class CreditCard extends ValidatorAbstract
{

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function isValid($value)
    {
        $value = preg_replace('/\D/', '', $value);

        // Set the string length and parity
        $number_length = strlen($value);
        $parity        = $number_length % 2;

        // Loop through each digit and do the maths
        $total = 0;
        for ($i = 0; $i < $number_length; $i++) {
            $digit = $value[$i];
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

    public static function luhnTest(string $num)
    {
        $len = strlen($num);
        $sum = 0;

        for ($i = $len - 1; $i >= 0; $i--) {
            $ord = ord($num[$i]);

            if (($len - 1) & $i) {
                $sum += $ord;
            } else {
                $sum += $ord / 5 + (2 * $ord) % 10;
            }
        }

        return $sum % 10 == 0;
    }
}
