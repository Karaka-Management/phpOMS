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
 * Base64Url encoding class
 *
 * @package phpOMS\Utils\Encoding
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Base64Url
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
     * Encode source text
     *
     * @param string $source Source to encode
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function encode(string $source) : string
    {
        $data64    = \base64_encode($source);
        $data64Url = \strtr($data64, '+/', '-_');

        return \rtrim($data64Url, '=');
    }

    /**
     * Decodes text
     *
     * @param string $b64 Encoded value to decode
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function decode(string $b64) : string
    {
        $data = \strtr($b64, '-_', '+/');

        return \base64_decode($data, false);
    }
}
