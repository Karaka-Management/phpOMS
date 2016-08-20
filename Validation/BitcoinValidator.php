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
namespace phpOMS\Validation;

/**
 * Bitcoin validator.
 *
 * @category   Framework
 * @package    phpOMS\Utils\TaskSchedule
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class BitcoinValidator extends ValidatorAbstract
{
    /**
     * Validate bitcoin.
     *
     * @param string $addr Bitcoin address
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function isValid(string $addr)  : bool
    {
        try {
            $decoded = self::decodeBase58($addr);

            $d1 = hash("sha256", substr($decoded, 0, 21), true);
            $d2 = hash("sha256", $d1, true);

            if (substr_compare($decoded, $d2, 21, 4)) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            self::$msg = $e->getMessage();

            return false;
        }
    }

    /**
     * Decode base 58 bitcoin address.
     *
     * @param string $addr Bitcoin address
     *
     * @return string
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function decodeBase58(string $addr) : string
    {
        $alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';

        $out    = array_fill(0, 25, 0);
        $length = strlen($addr);

        for ($i = 0; $i < $length; $i++) {
            if (($p = strpos($alphabet, $addr[$i])) === false) {
                throw new \Exception('Invalid character found in address.');
            }

            $c = $p;
            for ($j = 25; $j--;) {
                $c += (int) (58 * $out[$j]);
                $out[$j] = (int) ($c % 256);
                $c /= 256;
                $c = (int) $c;
            }

            if ($c !== 0) {
                throw new \Exception('Bitcoin address too long.');
            }
        }

        $result = '';
        foreach ($out as $val) {
            $result .= chr($val);
        }

        return $result;
    }
}