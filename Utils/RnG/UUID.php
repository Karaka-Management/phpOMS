<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\RnG
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\RnG;

/**
 * UUID generator.
 *
 * @package phpOMS\Utils\RnG
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class UUID
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
     * Get default random UUID
     *
     * @param int<8, max> $length Result length in bytes
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     *
     * @since 1.0.0
     */
    public static function default(int $length = 16) : string
    {
        /** @phpstan-ignore-next-line */
        if ($length < 8) {
            throw new \InvalidArgumentException();
        }

        return \pack('Q', \time()) . ($length > 8 ? \random_bytes($length - 8) : '');
    }
}
