<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Utils\Encoding
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Utils\Encoding;

/**
 * XOR encoding class
 *
 * @package    phpOMS\Utils\Encoding
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class XorEncoding
{

    /**
     * Decode text
     *
     * @param string $raw Source to encode
     * @param string $key Key used for decoding
     *
     * @return string
     *
     * @since  1.0.0
     */
    public static function decode(string $raw, string $key) : string
    {
        return self::encode($raw, $key);
    }

    /**
     * Encode source text
     *
     * @param string $source Source to encode
     * @param string $key    Key used for encoding
     *
     * @return string
     *
     * @since  1.0.0
     */
    public static function encode(string $source, string $key) : string
    {
        $result    = '';
        $length    = \strlen($source);
        $keyLength = \strlen($key) - 1;

        for ($i = 0, $j = 0; $i < $length; ++$i, $j++) {
            if ($j > $keyLength) {
                $j = 0;
            }

            $ascii   = \ord($source[$i]) ^ \ord($key[$j]);
            $result .= \chr($ascii);
        }

        return $result;
    }
}
