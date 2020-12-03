<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Utils\IO\Zip
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Zip;

/**
 * Archive interface
 *
 * @package phpOMS\Utils\IO\Zip
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
interface ArchiveInterface
{
    /**
     * Create archive.
     *
     * @param string|array $sources     Files and directories to compress
     * @param string       $destination Output destination
     * @param bool         $overwrite   Overwrite if destination is existing
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function pack(string|array $sources, string $destination, bool $overwrite = false) : bool;

    /**
     * Unpack archive.
     *
     * @param string $source      File to decompress
     * @param string $destination Output destination
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function unpack(string $source, string $destination) : bool;
}
