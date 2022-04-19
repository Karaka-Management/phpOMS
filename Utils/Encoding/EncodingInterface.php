<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\Encoding
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Encoding;

/**
 * Encoding Interface
 *
 * @package phpOMS\Utils\Encoding
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
interface EncodingInterface
{
    /**
     * Encode source text
     *
     * @param string $source Source text to decode
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function encode(mixed $source);

    /**
     * Dedecodes text
     *
     * @param string $decoded encoded text to dedecode
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function decode(mixed $decoded);
}
