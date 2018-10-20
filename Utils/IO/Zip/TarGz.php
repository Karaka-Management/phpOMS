<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Utils\IO\Zip
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Zip;

use phpOMS\System\File\Local\File;

/**
 * Zip class for handling zip files.
 *
 * Providing basic zip support
 *
 * @package    phpOMS\Utils\IO\Zip
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class TarGz implements ArchiveInterface
{
    /**
     * {@inheritdoc}
     */
    public static function pack($source, string $destination, bool $overwrite = true) : bool
    {
        if (!$overwrite && \file_exists($destination)) {
            return false;
        }

        if (!Tar::pack($source, $destination . '.tmp', $overwrite)) {
            return false;
        }

        $pack = Gz::pack($destination . '.tmp', $destination, $overwrite);
        \unlink($destination . '.tmp');

        return $pack;
    }

    /**
     * {@inheritdoc}
     */
    public static function unpack(string $source, string $destination) : bool
    {
        if (!\file_exists($source)) {
            return false;
        }

        if (!Gz::unpack($source, $destination . '/' . File::name($source) . '.tmp')) {
            return false;
        }

        $unpacked = Tar::unpack($destination . '/' . File::name($source) . '.tmp', $destination);
        \unlink($destination . '/' . File::name($source) . '.tmp');

        return $unpacked;
    }
}
