<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\IO\Zip
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Zip;

use phpOMS\System\File\Local\File;

/**
 * Zip class for handling zip files.
 *
 * Providing basic zip support
 *
 * @package phpOMS\Utils\IO\Zip
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class TarGz implements ArchiveInterface
{
    /**
     * {@inheritdoc}
     */
    public static function pack(string | array $source, string $destination, bool $overwrite = false) : bool
    {
        $destination = \str_replace('\\', '/', $destination);
        if (!$overwrite && \is_file($destination)) {
            return false;
        }

        if (!Tar::pack($source, $destination . '.tmp', $overwrite)) {
            return false;
        }

        $pack = Gz::pack($destination . '.tmp', $destination, $overwrite);

        if ($pack) {
            \unlink($destination . '.tmp');
        }

        return $pack;
    }

    /**
     * {@inheritdoc}
     */
    public static function unpack(string $source, string $destination) : bool
    {
        $destination = \str_replace('\\', '/', $destination);
        if (!\is_dir($destination) || !\is_file($source)) {
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
