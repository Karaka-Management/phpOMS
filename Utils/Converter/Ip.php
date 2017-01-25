<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Utils\Converter;

/**
 * Ip converter.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Ip
{
    /* public */ const IP_TABLE_PATH = __DIR__ . '/../../Localization/Default/Ip/ipGeoLocation.csv';
    /* public */ const IP_TABLE_ITERATIONS = 100;

    private function __construct()
    {
    }

    public static function ip2Country(string $ip) : string
    {
        $fh = fopen(self::IP_TABLE_PATH, 'r');

        fseek($fh, 0, SEEK_END);
        $end = ftell($fh);
        fseek($fh, 0);
        $start   = 0;
        $current = $start;

        $ip      = self::ip2Float($ip);
        $country = '';
        $counter = 0;

        while ($counter < self::IP_TABLE_ITERATIONS) {
            $line = fgets($fh, 150);
            if ($current !== 0) {
                $line = fgets($fh, 150);
            }

            $split = explode(',', $line);

            if ($ip >= $split[0] && $ip <= $split[1]) {
                $country = $split[2];
                break;
            }

            if ($ip > $split[1]) {
                $larger = true;
                $start  = $current;
                fseek($fh, ($end - $current) / 2, SEEK_CUR);
            } else {
                $larger = false;
                $end    = $current;
                fseek($fh, ($start - $current) / 2, SEEK_CUR);
            }

            $counter++;
            $current = ftell($fh);
        }

        fclose($fh);

        return $country;
    }

    public static function ip2Float(string $ip) : float
    {
        $split = explode('.', $ip);

        return $split[0] * (256 ** 3) + $split[1] * (256 ** 2) + $split[2] * (256 ** 1) + $split[3];
    }
}