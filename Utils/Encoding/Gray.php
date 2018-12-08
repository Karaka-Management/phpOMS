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
 * Gray encoding class
 *
 * @package    phpOMS\Utils\Encoding
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class Gray
{
    /**
     * Encode source text
     *
     * @param int $source Source to encode
     *
     * @return int
     *
     * @since  1.0.0
     */
    public static function encode(int $source) : int
    {
        return $source ^ ($source >> 1);
    }

    /**
     * Dedecodes text
     *
     * @param int $gray encoded value to dedecode
     *
     * @return int
     *
     * @since  1.0.0
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
