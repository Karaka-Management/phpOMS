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
namespace phpOMS\Utils\Encoding;

/**
 * Gray encoding class
 *
 * @category   Framework
 * @package    phpOMS\Utils
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Ceasar  {
    const LIMIT_LOWER = 0;
    const LIMIT_UPPER = 127;

    /**
     * {@inheritdoc}
     */
    public static function encode(string $source, string $key) : string
    {
        $result = '';
        $length = strlen($source);
        $keyLength = strlen($key)-1;

        for($i = 0, $j = 0; $i < $length; $i++, $j++) {
            if($j > $keyLength) {
                $j = 0;
            }

            $ascii = ord($source[$i]) + ord($key[$j]);

            if($ascii > self::LIMIT_UPPER) {
                $ascii -= self::LIMIT_UPPER;
            }

            $result .= char($ascii);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public static function decode(string $raw, string $key) : string
    {
        $result = '';
        $length = strlen($raw);
        $keyLength = strlen($key)-1;

        for($i = 0, $j = 0; $i < $length; $i++, $j++) {
            if($j > $keyLength) {
                $j = 0;
            }

            $ascii = ord($raw[$i]) - ord($key[$j]);

            if($ascii < self::LIMIT_LOWER) {
                $ascii += self::LIMIT_LOWER;
            }

            $result .= char($ascii);
        }

        return $result;
    }
}