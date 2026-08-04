<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\Encoding
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Encoding;

/**
 * XOR encoding class
 *
 * @package phpOMS\Utils\Encoding
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class XorEncoding
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Decode text
     *
     * @param string $raw Source to encode
     * @param string $key Key used for decoding
     *
     * @return string
     *
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public static function encode(string $source, string $key) : string
    {
        $result    = '';
        $length    = \strlen($source);
        $keyLength = \strlen($key) - 1;

        for ($i = 0, $j = 0; $i < $length; ++$i, ++$j) {
            if ($j > $keyLength) {
                $j = 0;
            }

            $ascii = \ord($source[$i]) ^ \ord($key[$j]);
            $result .= \chr($ascii);
        }

        return $result;
    }
}
