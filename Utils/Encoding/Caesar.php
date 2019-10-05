<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Utils\Encoding
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils\Encoding;

/**
 * Gray encoding class
 *
 * @package phpOMS\Utils\Encoding
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Caesar
{
    /**
     * ASCII lower char limit.
     *
     * @var   int
     * @since 1.0.0
     */
    public const LIMIT_LOWER = 0;

    /**
     * ASCII upper char limit.
     *
     * @var   int
     * @since 1.0.0
     */
    public const LIMIT_UPPER = 127;

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

            $ascii = \ord($source[$i]) + \ord($key[$j]);

            if ($ascii > self::LIMIT_UPPER) {
                $ascii = self::LIMIT_LOWER + ($ascii - self::LIMIT_UPPER);
            }

            $result .= \chr($ascii);
        }

        return $result;
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
        $result    = '';
        $length    = \strlen($raw);
        $keyLength = \strlen($key) - 1;

        for ($i = 0, $j = 0; $i < $length; ++$i, ++$j) {
            if ($j > $keyLength) {
                $j = 0;
            }

            $ascii = \ord($raw[$i]) - \ord($key[$j]);

            if ($ascii < self::LIMIT_LOWER) {
                $ascii = self::LIMIT_UPPER + ($ascii - self::LIMIT_LOWER);
            }

            $result .= \chr($ascii);
        }

        return $result;
    }
}
