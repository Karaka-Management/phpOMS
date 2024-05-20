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
 * Gray encoding class
 *
 * @package phpOMS\Utils\Encoding
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Gray
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
     * @param int $source Source to encode
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function encode(int $source) : int
    {
        return $source ^ ($source >> 1);
    }

    /**
     * Decodes text
     *
     * @param int $gray encoded value to decode
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function decode(int $gray) : int
    {
        $source = $gray;

        while ($gray >>= 1) {
            $source ^= $gray;
        }

        return $source;
    }
}
